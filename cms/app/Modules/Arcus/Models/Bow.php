<?php

namespace App\Modules\Arcus\Models;

use App\Modules\Arcus\Support\ArcusCatalog;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Bow extends Model
{
    protected $connection = 'legacy';

    protected $table = 'bow';

    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
        'status',
        'price',
        'discount',
        'active',
        'range_id',
        'instrument_id',
        'style_id',
        'shape_id',
        'size_id',
        'wood_id',
        'origin_id',
        'color_id',
        'button_material_id',
        'frog_material_id',
        'slide_material_id',
        'tip_material_id',
        'garnish_id',
        'stick_length',
        'total_length',
        'stick_weight',
        'total_weight',
        'balance_point',
        'density',
        'speed',
        'elasticity',
        'frequency',
        'damping',
        'flexibility_id',
        'responsiveness_id',
        'handling_id',
        'natural_pressure_id',
        'projection_id',
        'sustain_id',
        'tone_id',
        'articulation_id',
        'short_trait',
        'notes',
    ];

    protected $casts = [
        'active' => 'boolean',
        'price' => 'integer',
        'discount' => 'integer',
    ];

    protected $appends = [
        'display_title',
        'price_label',
        'public_url',
        'range_name',
        'instrument_name',
        'photo_count',
        'main_image_url',
        'photo_public_paths',
        'photo_directory_path',
    ];

    protected function displayTitle(): Attribute
    {
        return Attribute::get(function (): string {
            $parts = array_filter([
                $this->range_name,
                $this->id ? 'n°'.$this->id : null,
                $this->name ? '"'.$this->name.'"' : null,
            ]);

            return implode(' ', $parts) ?: (string) $this->code;
        });
    }

    protected function priceLabel(): Attribute
    {
        return Attribute::get(function (): ?string {
            if ($this->price === null) {
                return null;
            }

            return ArcusCatalog::formatPrice((int) $this->price);
        });
    }

    protected function publicUrl(): Attribute
    {
        return Attribute::get(fn (): string => route('arcus.show', strtolower((string) $this->code)));
    }

    protected function rangeName(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->lookupName('range', 'range_id'));
    }

    protected function instrumentName(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->lookupName('instrument', 'instrument_id'));
    }

    protected function photoCount(): Attribute
    {
        return Attribute::get(fn (): int => count($this->photoPaths()));
    }

    protected function mainImageUrl(): Attribute
    {
        return Attribute::get(function (): ?string {
            $paths = $this->photo_public_paths;

            return $paths[0] ?? null;
        });
    }

    protected function photoPublicPaths(): Attribute
    {
        return Attribute::get(fn (): array => array_map(
            fn (string $path): string => '/assets/images/archets/' . $this->normalizedCode() . '/' . basename($path),
            $this->photoPaths(),
        ));
    }

    protected function photoDirectoryPath(): Attribute
    {
        return Attribute::get(fn (): string => 'public/assets/images/archets/' . $this->normalizedCode());
    }

    protected function lookupName(string $table, string $foreignKey): ?string
    {
        $id = $this->getAttribute($foreignKey);

        if ($id === null) {
            return null;
        }

        return DB::connection('legacy')
            ->table($table)
            ->where('id', $id)
            ->value('name');
    }

    protected function photoPaths(): array
    {
        $code = $this->normalizedCode();
        $dir = public_path('assets/images/archets/'.$code);

        if ($code === '' || ! File::isDirectory($dir)) {
            return [];
        }

        $mainImages = File::glob($dir.'/main.{jpg,jpeg,png,webp,heic}', GLOB_BRACE);
        $images = $mainImages ?: File::glob($dir.'/*.{jpg,jpeg,png,webp,heic}', GLOB_BRACE);

        sort($images);

        return $images;
    }

    protected function normalizedCode(): string
    {
        return strtolower(trim((string) $this->code));
    }
}
