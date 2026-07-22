<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class MaracujaDatabaseBackupCommand extends Command
{
    protected $signature = 'maracuja:db:backup {--name= : Nom explicite du snapshot}';

    protected $description = 'Crée un snapshot logique privé de toutes les données de la base courante.';

    public function handle(): int
    {
        $name = filled($this->option('name'))
            ? Str::slug((string) $this->option('name'))
            : now()->format('Ymd-His');
        $directory = storage_path('app/private/database-backups');
        $path = $directory.DIRECTORY_SEPARATOR.$name.'.json';

        if (! is_dir($directory) && ! mkdir($directory, 0750, true) && ! is_dir($directory)) {
            throw new RuntimeException("Impossible de créer {$directory}.");
        }
        if (is_file($path)) {
            $this->error("Le snapshot existe déjà : {$path}");

            return self::FAILURE;
        }

        $tables = collect(DB::select(
            'select table_name from information_schema.tables where table_schema = database() and table_type = ?',
            ['BASE TABLE'],
        ))->map(fn (object $row): string => (string) ($row->TABLE_NAME ?? $row->table_name))
            ->sort()
            ->values();

        $snapshot = [
            'version' => 2,
            'database' => DB::connection()->getDatabaseName(),
            'created_at' => now()->toIso8601String(),
            'tables' => $tables->mapWithKeys(function (string $table): array {
                $columns = collect(DB::select(
                    'select column_name from information_schema.columns where table_schema = database() and table_name = ? order by ordinal_position',
                    [$table],
                ))->map(fn (object $row): string => (string) ($row->COLUMN_NAME ?? $row->column_name))
                    ->values()
                    ->all();

                if ($columns === []) {
                    throw new RuntimeException("Impossible de lire les colonnes de la table {$table}.");
                }

                $quotedTable = '`'.str_replace('`', '``', $table).'`';
                $orderColumn = in_array('id', $columns, true) ? 'id' : $columns[0];
                $quotedOrderColumn = '`'.str_replace('`', '``', $orderColumn).'`';

                return [$table => [
                    'columns' => $columns,
                    'rows' => collect(DB::select("select * from {$quotedTable} order by {$quotedOrderColumn}"))
                        ->map(fn (object $row): array => collect((array) $row)
                            ->map(fn (mixed $value): mixed => $this->encodeValue($value))
                            ->all())
                        ->all(),
                ]];
            })->all(),
        ];
        $json = json_encode($snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        if (file_put_contents($path, $json."\n", LOCK_EX) === false) {
            throw new RuntimeException("Impossible d’écrire {$path}.");
        }

        $this->info('Snapshot de base créé sans modifier les données.');
        $this->line('Base : '.$snapshot['database']);
        $this->line('Tables : '.$tables->count());
        $this->line('Fichier privé : '.$path);

        return self::SUCCESS;
    }

    private function encodeValue(mixed $value): mixed
    {
        if (! is_string($value) || mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        return [
            '__maracuja_encoding' => 'base64',
            'data' => base64_encode($value),
        ];
    }
}
