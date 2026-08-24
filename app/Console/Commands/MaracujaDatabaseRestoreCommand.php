<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class MaracujaDatabaseRestoreCommand extends Command
{
    protected $signature = 'maracuja:db:restore
        {path : Snapshot JSON créé par maracuja:db:backup}
        {--force : Autorise la restauration destructive après migrate:fresh}';

    protected $description = 'Restaure les tables canoniques Maracuja depuis un snapshot local contrôlé.';

    public function handle(): int
    {
        if (! $this->option('force')) {
            $this->error('Ajoutez --force après avoir exécuté migrate:fresh.');

            return self::FAILURE;
        }

        $snapshot = json_decode((string) file_get_contents($this->argument('path')), true);
        $tables = is_array($snapshot) ? ($snapshot['tables'] ?? null) : null;

        if (($snapshot['version'] ?? null) !== 2 || ! is_array($tables)) {
            throw new RuntimeException('Snapshot de base invalide.');
        }

        $tables = collect($tables)
            ->filter(fn (mixed $data, string $table): bool => str_starts_with($table, 'cms_') && is_array($data))
            ->all();

        $existing = collect(DB::select('select table_name from information_schema.tables where table_schema = database()'))
            ->map(fn (object $row): string => (string) ($row->TABLE_NAME ?? $row->table_name))
            ->all();
        $missing = array_diff(array_keys($tables), $existing);

        if ($missing !== []) {
            throw new RuntimeException('Schéma incomplet : '.implode(', ', $missing));
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            DB::transaction(function () use ($tables): void {
                foreach (array_keys($tables) as $table) {
                    DB::table($this->logicalTable($table))->delete();
                }

                foreach ($tables as $table => $data) {
                    $columns = $data['columns'] ?? [];
                    $rows = $data['rows'] ?? [];

                    if (! is_array($columns) || ! is_array($rows)) {
                        throw new RuntimeException("Table invalide dans le snapshot : {$table}");
                    }

                    foreach (array_chunk($rows, 250) as $chunk) {
                        DB::table($this->logicalTable($table))->insert(array_map(
                            fn (array $row): array => array_intersect_key($this->decodeRow($row), array_flip($columns)),
                            $chunk,
                        ));
                    }
                }
            });
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->info('Base canonique restaurée : '.count($tables).' tables cms_.');

        return self::SUCCESS;
    }

    /** @param array<string, mixed> $row */
    private function decodeRow(array $row): array
    {
        return array_map(function (mixed $value): mixed {
            if (is_array($value) && ($value['__maracuja_encoding'] ?? null) === 'base64') {
                return base64_decode((string) ($value['data'] ?? ''), true);
            }

            return $value;
        }, $row);
    }

    private function logicalTable(string $table): string
    {
        return str_starts_with($table, 'cms_') ? substr($table, 4) : $table;
    }
}
