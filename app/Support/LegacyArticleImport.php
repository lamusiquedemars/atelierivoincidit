<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMXPath;
use RuntimeException;

class LegacyArticleImport
{
    /** @return array<int, array<string, mixed>> */
    public static function records(string $path, ?callable $mediaIdForSource = null): array
    {
        $sql = file_get_contents($path);

        if ($sql === false) {
            throw new RuntimeException("Impossible de lire l'export SQL : {$path}");
        }

        $records = [];
        $offset = 0;

        while (($start = strpos($sql, 'INSERT INTO `article`', $offset)) !== false) {
            $valuesPosition = strpos($sql, 'VALUES', $start);
            if ($valuesPosition === false) {
                throw new RuntimeException('Instruction INSERT article incomplète.');
            }

            $columnsStart = strpos($sql, '(', $start);
            $columnsEnd = strpos($sql, ')', $columnsStart);
            $columns = array_map(
                fn (string $column): string => trim($column, ' `'),
                explode(',', substr($sql, $columnsStart + 1, $columnsEnd - $columnsStart - 1)),
            );
            [$statement, $offset] = self::readStatement($sql, $valuesPosition + 6);

            foreach (self::tuples($statement) as $tuple) {
                $values = self::values($tuple);
                if (count($columns) !== count($values)) {
                    throw new RuntimeException('Nombre de colonnes incohérent dans un article historique.');
                }

                $legacy = array_combine($columns, $values);
                $records[] = [
                    'legacy_id' => (int) $legacy['id'],
                    'slug' => (string) $legacy['slug'],
                    'title' => (string) $legacy['title'],
                    'excerpt' => $legacy['resume'],
                    'body_blocks' => self::blocks((string) $legacy['content'], $mediaIdForSource),
                    // Les fichiers historiques ne sont pas dans la médiathèque centrale :
                    // on ne crée donc pas de chemin cassé dans le nouveau site.
                    'image_path' => null,
                    'is_published' => (bool) $legacy['enabled'],
                    'published_at' => $legacy['date_published'],
                    'created_at' => $legacy['date_published'],
                    'updated_at' => $legacy['updated_at'] ?? $legacy['date_published'],
                    'legacy_images' => self::imagePaths((string) $legacy['content'], $legacy['image']),
                ];
            }
        }

        return $records;
    }

    /** @return array<int, array<string, string>> */
    private static function blocks(string $html, ?callable $mediaIdForSource = null): array
    {
        $document = new DOMDocument;
        libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="utf-8" ?><article>'.$html.'</article>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $root = $document->getElementsByTagName('article')->item(0);
        if (! $root instanceof DOMElement) {
            return [['type' => 'rich_text', 'text' => $html]];
        }

        $blocks = [];
        foreach (iterator_to_array($root->childNodes) as $node) {
            if ($node->nodeType === XML_TEXT_NODE && trim($node->textContent) === '') {
                continue;
            }

            $tag = strtolower($node->nodeName);
            if (in_array($tag, ['h2', 'h3'], true)) {
                $blocks[] = [
                    'type' => 'heading',
                    'level' => substr($tag, 1),
                    'heading' => trim($node->textContent),
                ];

                continue;
            }

            if (in_array($tag, ['figure', 'p'], true)) {
                $image = (new DOMXPath($document))->query('.//img', $node)->item(0);
                if ($image instanceof DOMElement) {
                    $mediaId = $mediaIdForSource ? $mediaIdForSource($image->getAttribute('src')) : null;
                    if ($mediaId) {
                        $caption = (new DOMXPath($document))->query('.//figcaption', $node)->item(0);
                        $blocks[] = array_filter([
                            'type' => 'image',
                            'media_id' => $mediaId,
                            'alt' => $image->getAttribute('alt'),
                            'caption' => $caption ? trim($caption->textContent) : null,
                        ], fn (mixed $value): bool => $value !== null && $value !== '');
                    }

                    continue;
                }
            }

            if ($tag === 'table') {
                $rows = [];
                foreach ((new DOMXPath($document))->query('.//tr', $node) as $row) {
                    $cells = [];
                    foreach ((new DOMXPath($document))->query('./th|./td', $row) as $cell) {
                        $cells[] = trim($cell->textContent);
                    }
                    if ($cells !== []) {
                        $rows[] = implode(' | ', $cells);
                    }
                }
                if ($rows !== []) {
                    $blocks[] = ['type' => 'table', 'table_rows' => implode("\n", $rows)];

                    continue;
                }
            }

            $rendered = trim($document->saveHTML($node) ?: '');
            if ($rendered !== '') {
                $blocks[] = ['type' => 'rich_text', 'text' => $rendered];
            }
        }

        return $blocks;
    }

    /** @return array<int, string> */
    private static function imagePaths(string $html, mixed $cover): array
    {
        preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $matches);

        return array_values(array_unique(array_filter([
            is_string($cover) && $cover !== '' ? $cover : null,
            ...$matches[1],
        ])));
    }

    /** @return array{0: string, 1: int} */
    private static function readStatement(string $sql, int $offset): array
    {
        $depth = 0;
        $quote = false;
        $escaped = false;
        $start = $offset;

        for ($index = $offset, $length = strlen($sql); $index < $length; $index++) {
            $character = $sql[$index];
            if ($quote) {
                if ($escaped) {
                    $escaped = false;
                } elseif ($character === '\\') {
                    $escaped = true;
                } elseif ($character === "'") {
                    $quote = false;
                }

                continue;
            }
            if ($character === "'") {
                $quote = true;
            } elseif ($character === '(') {
                $depth++;
            } elseif ($character === ')') {
                $depth--;
            } elseif ($character === ';' && $depth === 0) {
                return [substr($sql, $start, $index - $start), $index + 1];
            }
        }

        throw new RuntimeException('Fin d’instruction INSERT article introuvable.');
    }

    /** @return array<int, string> */
    private static function tuples(string $statement): array
    {
        $tuples = [];
        $depth = 0;
        $start = null;
        $quote = false;
        $escaped = false;

        for ($index = 0, $length = strlen($statement); $index < $length; $index++) {
            $character = $statement[$index];
            if ($quote) {
                if ($escaped) {
                    $escaped = false;
                } elseif ($character === '\\') {
                    $escaped = true;
                } elseif ($character === "'") {
                    $quote = false;
                }

                continue;
            }
            if ($character === "'") {
                $quote = true;
            } elseif ($character === '(') {
                if ($depth++ === 0) {
                    $start = $index + 1;
                }
            } elseif ($character === ')' && --$depth === 0 && $start !== null) {
                $tuples[] = substr($statement, $start, $index - $start);
            }
        }

        return $tuples;
    }

    /** @return array<int, mixed> */
    private static function values(string $tuple): array
    {
        $values = [];
        $current = '';
        $quote = false;
        $escaped = false;

        foreach (str_split($tuple) as $character) {
            if ($quote) {
                $current .= $character;
                if ($escaped) {
                    $escaped = false;
                } elseif ($character === '\\') {
                    $escaped = true;
                } elseif ($character === "'") {
                    $quote = false;
                }

                continue;
            }
            if ($character === "'") {
                $quote = true;
                $current .= $character;
            } elseif ($character === ',') {
                $values[] = self::value($current);
                $current = '';
            } else {
                $current .= $character;
            }
        }
        $values[] = self::value($current);

        return $values;
    }

    private static function value(string $value): mixed
    {
        $value = trim($value);
        if (strtoupper($value) === 'NULL') {
            return null;
        }
        if (str_starts_with($value, "'") && str_ends_with($value, "'")) {
            return stripcslashes(substr($value, 1, -1));
        }

        return is_numeric($value) ? (int) $value : $value;
    }
}
