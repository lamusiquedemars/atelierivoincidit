<?php

namespace App\Console\Commands;

use App\Modules\Arcus\Models\ArcusBowMedia;
use App\Modules\Arcus\Models\Bow;
use App\Modules\Media\Models\MediaAsset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SyncArcusMediaManifestCommand extends Command
{
    protected $signature = 'arcus:media-manifest
        {path : Fichier JSON de manifeste}
        {--export : Exporte les médias Arcus locaux dans le manifeste}
        {--dry-run : Vérifie le manifeste et les fichiers sans écrire}
        {--force : Autorise l’import en production}';

    protected $description = 'Transfère les métadonnées et liaisons des médias Arcus via un manifeste portable.';

    public function handle(): int
    {
        return $this->option('export') ? $this->export() : $this->import();
    }

    private function export(): int
    {
        $items = ArcusBowMedia::query()
            ->with(['bow:id,code', 'media'])
            ->orderBy('arcus_bow_id')
            ->orderBy('position')
            ->get()
            ->map(function (ArcusBowMedia $link): array {
                $media = $link->media;

                return [
                    'bow_code' => $link->bow->code,
                    'position' => $link->position,
                    'caption' => $link->caption,
                    'media' => collect($media->only([
                        'type', 'disk', 'path', 'thumbnail_path', 'original_name', 'display_name',
                        'mime_type', 'extension', 'size', 'width', 'height', 'alt_text', 'caption', 'credit', 'checksum',
                    ]))->filter(fn (mixed $value, string $key): bool => $value !== null || in_array($key, ['type', 'disk', 'path'], true))->all(),
                ];
            })
            ->values()
            ->all();

        $this->validateItems($items, false);
        $payload = json_encode(['version' => 1, 'items' => $items], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        if (file_put_contents($this->argument('path'), $payload) === false) {
            $this->error('Impossible d’écrire le manifeste.');

            return self::FAILURE;
        }

        $this->info('Manifeste exporté : '.count($items).' liaisons Arcus.');

        return self::SUCCESS;
    }

    private function import(): int
    {
        $payload = json_decode((string) file_get_contents($this->argument('path')), true);
        $items = is_array($payload) ? ($payload['items'] ?? null) : null;

        if (($payload['version'] ?? null) !== 1 || ! is_array($items)) {
            $this->error('Manifeste Media invalide.');

            return self::FAILURE;
        }

        $this->validateItems($items, true);

        if ($this->option('dry-run')) {
            $this->info('Import à blanc : '.count($items).' liaisons Media Arcus valides.');

            return self::SUCCESS;
        }

        if (! $this->option('force')) {
            $this->error('Ajoutez --force pour importer ce manifeste.');

            return self::FAILURE;
        }

        DB::transaction(function () use ($items): void {
            foreach ($items as $item) {
                $bow = Bow::query()->where('code', $item['bow_code'])->firstOrFail();
                $media = MediaAsset::query()->updateOrCreate(
                    ['disk' => $item['media']['disk'], 'path' => $item['media']['path']],
                    $item['media'],
                );

                ArcusBowMedia::query()->updateOrCreate(
                    ['arcus_bow_id' => $bow->id, 'media_asset_id' => $media->id],
                    ['position' => $item['position'], 'caption' => $item['caption']],
                );
            }
        });

        $this->info('Import Media terminé : '.count($items).' liaisons Arcus synchronisées.');

        return self::SUCCESS;
    }

    /** @param array<int, array<string, mixed>> $items */
    private function validateItems(array $items, bool $requireFiles): void
    {
        foreach ($items as $item) {
            $media = $item['media'] ?? null;

            if (! is_array($media)
                || ! is_string($item['bow_code'] ?? null)
                || ! is_int($item['position'] ?? null)
                || ($media['disk'] ?? null) !== 'public'
                || ! is_string($media['path'] ?? null)
                || ! str_starts_with($media['path'], 'media/images/')) {
                throw new \RuntimeException('Manifeste Media Arcus invalide.');
            }

            if ($requireFiles && ! Storage::disk('public')->fileExists($media['path'])) {
                throw new \RuntimeException('Fichier Media introuvable : '.$media['path']);
            }
        }
    }
}
