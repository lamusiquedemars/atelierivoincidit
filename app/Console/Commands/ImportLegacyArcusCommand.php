<?php

namespace App\Console\Commands;

use App\Modules\Arcus\Models\ArcusBowMedia;
use App\Modules\Arcus\Models\ArcusTerm;
use App\Modules\Arcus\Models\Bow;
use App\Modules\Media\Models\MediaAsset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImportLegacyArcusCommand extends Command
{
    protected $signature = 'arcus:import-legacy {--dry-run : Analyse sans écrire} {--force : Autorise l’import en production}';

    protected $description = 'Importe le catalogue historique Arcus dans les tables natives sans supprimer les données source.';

    public function handle(): int
    {
        if (! $this->option('dry-run') && ! $this->option('force')) {
            $this->error('Ajoutez --force pour écrire. Utilisez --dry-run pour contrôler sans modifier la base.');

            return self::FAILURE;
        }

        $legacy = DB::connection('legacy');
        $bowCount = $legacy->table('bow')->count();

        if ($this->option('dry-run')) {
            $this->info("Import à blanc : {$bowCount} archets historiques seraient importés.");
            $this->line('Aucune écriture, aucun fichier et aucune donnée source ne sont modifiés.');

            return self::SUCCESS;
        }

        $terms = $this->importTerms($legacy);
        $imported = 0;
        $images = 0;

        foreach ($legacy->table('bow')->orderBy('id')->get() as $legacyBow) {
            $bow = Bow::query()->updateOrCreate(
                ['legacy_id' => $legacyBow->id],
                $this->bowAttributes($legacyBow, $terms),
            );

            $images += $this->importImages($bow, (string) $legacyBow->code);
            $imported++;
        }

        $this->info("Import terminé : {$imported} archets et {$images} photos traités.");
        $this->line('Les tables historiques restent intactes jusqu’à la validation manuelle.');

        return self::SUCCESS;
    }

    /** @return array<string, array<int, int>> */
    private function importTerms($legacy): array
    {
        $definitions = [
            'range' => ['table' => 'range', 'slug' => true, 'description' => true],
            'instrument' => ['table' => 'instrument'],
            'style' => ['table' => 'style'],
            'shape' => ['table' => 'shape'],
            'size' => ['table' => 'size'],
            'wood' => ['table' => 'wood'],
            'origin' => ['table' => 'origin'],
            'color' => ['table' => 'color'],
            'material' => ['table' => 'material'],
            'garnish' => ['table' => 'garnish'],
            'quality' => ['table' => 'quality'],
        ];

        $terms = [];

        foreach ($definitions as $type => $definition) {
            $terms[$type] = [];

            foreach ($legacy->table($definition['table'])->orderBy('id')->get() as $item) {
                $term = ArcusTerm::query()->updateOrCreate([
                    'type' => $type,
                    'legacy_id' => $item->id,
                ], [
                    'name' => trim((string) $item->name),
                    'group' => $type === 'quality' ? $item->type : null,
                    'slug' => ($definition['slug'] ?? false) ? $item->slug : null,
                    'description' => ($definition['description'] ?? false) ? $item->description : null,
                ]);

                $terms[$type][$item->id] = $term->id;
            }
        }

        return $terms;
    }

    private function bowAttributes(object $bow, array $terms): array
    {
        $term = fn (string $type, string $column): ?int => isset($bow->{$column})
            ? ($terms[$type][$bow->{$column}] ?? null)
            : null;

        return [
            'code' => strtolower(trim((string) $bow->code)),
            'name' => $bow->name,
            'status' => $bow->status ?: 'available',
            'price' => $bow->price,
            'discount' => $bow->discount,
            'active' => (bool) $bow->active,
            'range_id' => $term('range', 'range_id'),
            'instrument_id' => $term('instrument', 'instrument_id'),
            'style_id' => $term('style', 'style_id'),
            'shape_id' => $term('shape', 'shape_id'),
            'size_id' => $term('size', 'size_id'),
            'wood_id' => $term('wood', 'wood_id'),
            'origin_id' => $term('origin', 'origin_id'),
            'color_id' => $term('color', 'color_id'),
            'button_material_id' => $term('material', 'button_material_id'),
            'frog_material_id' => $term('material', 'frog_material_id'),
            'slide_material_id' => $term('material', 'slide_material_id'),
            'tip_material_id' => $term('material', 'tip_material_id'),
            'garnish_id' => $term('garnish', 'garnish_id'),
            'flexibility_id' => $term('quality', 'flexibility_id'),
            'responsiveness_id' => $term('quality', 'responsiveness_id'),
            'handling_id' => $term('quality', 'handling_id'),
            'natural_pressure_id' => $term('quality', 'natural_pressure_id'),
            'tone_id' => $term('quality', 'tone_id'),
            'projection_id' => $term('quality', 'projection_id'),
            'sustain_id' => $term('quality', 'sustain_id'),
            'articulation_id' => $term('quality', 'articulation_id'),
            ...collect(['stick_length', 'total_length', 'stick_weight', 'total_weight', 'balance_point', 'density', 'speed', 'elasticity', 'frequency', 'damping', 'short_trait', 'notes'])
                ->mapWithKeys(fn (string $field): array => [$field => $bow->{$field}])
                ->all(),
        ];
    }

    private function importImages(Bow $bow, string $code): int
    {
        $sourceDirectory = public_path('assets/images/archets/'.strtolower(trim($code)));

        if (! File::isDirectory($sourceDirectory)) {
            return 0;
        }

        $count = 0;

        foreach (File::files($sourceDirectory) as $position => $file) {
            if (! in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp'], true)) {
                continue;
            }

            $path = 'media/images/archets/'.strtolower(trim($code)).'/'.$file->getFilename();
            Storage::disk('public')->put($path, File::get($file->getPathname()));

            $media = MediaAsset::query()->updateOrCreate([
                'disk' => 'public',
                'path' => $path,
            ], [
                'type' => 'image',
                'original_name' => $file->getFilename(),
                'display_name' => 'Archet '.strtoupper(trim($code)),
                'mime_type' => $file->getMimeType(),
                'extension' => strtolower($file->getExtension()),
                'size' => $file->getSize(),
                'alt_text' => 'Archet '.strtoupper(trim($code)),
                'checksum' => hash_file('sha256', $file->getPathname()),
            ]);

            ArcusBowMedia::query()->updateOrCreate([
                'arcus_bow_id' => $bow->id,
                'media_asset_id' => $media->id,
            ], [
                'position' => $position + 1,
            ]);

            $count++;
        }

        return $count;
    }
}
