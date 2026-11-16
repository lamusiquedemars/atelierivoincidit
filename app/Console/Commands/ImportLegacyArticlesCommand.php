<?php

namespace App\Console\Commands;

use App\Modules\Articles\Models\Article;
use App\Support\LegacyArticleImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportLegacyArticlesCommand extends Command
{
    protected $signature = 'articles:import-legacy
        {--source= : Chemin de l’export SQL historique}
        {--dry-run : Contrôle sans écrire}
        {--update-existing : Met à jour les articles ayant le même slug}
        {--force : Autorise l’écriture}';

    protected $description = 'Importe les articles historiques vers le format body_blocks, sans tags ni style.';

    public function handle(): int
    {
        if (! $this->option('dry-run') && ! $this->option('force')) {
            $this->error('Ajoutez --dry-run pour contrôler ou --force pour écrire.');

            return self::FAILURE;
        }

        $source = $this->option('source') ?: base_path('archive/storage/private/ivoin2573774.sql');
        $records = LegacyArticleImport::records($source);
        $this->info(count($records).' articles historiques trouvés.');

        foreach ($records as $record) {
            $exists = Article::query()->where('slug', $record['slug'])->exists();
            $action = $exists ? ($this->option('update-existing') ? 'mettre à jour' : 'ignorer') : 'créer';
            $this->line(sprintf('%s : %s (%s)', $action, $record['title'], $record['slug']));
            if ($record['legacy_images'] !== []) {
                $this->warn('  Images historiques à rattacher à la médiathèque : '.implode(', ', $record['legacy_images']));
            }
        }

        if ($this->option('dry-run')) {
            $this->info('Import à blanc terminé : aucune donnée n’a été modifiée.');

            return self::SUCCESS;
        }

        DB::transaction(function () use ($records): void {
            foreach ($records as $record) {
                $article = Article::query()->where('slug', $record['slug'])->first();
                if ($article && ! $this->option('update-existing')) {
                    continue;
                }

                $attributes = collect($record)->except(['legacy_id', 'legacy_images'])->all();
                if ($article) {
                    $article->fill($attributes)->save();
                } else {
                    Article::query()->create($attributes);
                }
            }
        });

        $this->info('Import terminé. Les images signalées restent à migrer vers la médiathèque avant publication.');

        return self::SUCCESS;
    }
}
