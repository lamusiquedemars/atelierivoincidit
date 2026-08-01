<?php

namespace App\Modules\Arcus\Models;

use App\Modules\Arcus\Support\ArcusCatalog;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bow extends Model
{
    protected $table = 'arcus_bows';

    protected $fillable = [
        'code', 'name', 'status', 'price', 'discount', 'active',
        'range_id', 'instrument_id', 'style_id', 'shape_id', 'size_id', 'wood_id',
        'origin_id', 'color_id', 'button_material_id', 'frog_material_id',
        'slide_material_id', 'tip_material_id', 'garnish_id', 'stick_length',
        'total_length', 'stick_weight', 'total_weight', 'balance_point', 'density',
        'speed', 'elasticity', 'frequency', 'damping', 'flexibility_id',
        'responsiveness_id', 'handling_id', 'natural_pressure_id', 'projection_id',
        'sustain_id', 'tone_id', 'articulation_id', 'short_trait', 'notes',
    ];

    protected $casts = [
        'active' => 'boolean',
        'price' => 'integer',
        'discount' => 'integer',
    ];

    protected $appends = [
        'display_title', 'price_label', 'public_url', 'range_name', 'instrument_name',
        'photo_count', 'main_image_url',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ArcusBowMedia::class, 'arcus_bow_id')->orderBy('position')->orderBy('id');
    }

    public function range(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'range_id');
    }

    public function instrument(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'instrument_id');
    }

    public function style(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'style_id');
    }

    public function shape(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'shape_id');
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'size_id');
    }

    public function wood(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'wood_id');
    }

    public function origin(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'origin_id');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'color_id');
    }

    public function buttonMaterial(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'button_material_id');
    }

    public function frogMaterial(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'frog_material_id');
    }

    public function slideMaterial(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'slide_material_id');
    }

    public function tipMaterial(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'tip_material_id');
    }

    public function garnish(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'garnish_id');
    }

    public function flexibility(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'flexibility_id');
    }

    public function responsiveness(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'responsiveness_id');
    }

    public function handling(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'handling_id');
    }

    public function naturalPressure(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'natural_pressure_id');
    }

    public function tone(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'tone_id');
    }

    public function projection(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'projection_id');
    }

    public function sustain(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'sustain_id');
    }

    public function articulation(): BelongsTo
    {
        return $this->belongsTo(ArcusTerm::class, 'articulation_id');
    }

    protected function displayTitle(): Attribute
    {
        return Attribute::get(fn (): string => implode(' ', array_filter([
            $this->range_name,
            $this->id ? 'n°'.$this->id : null,
            $this->name ? '"'.$this->name.'"' : null,
        ])) ?: (string) $this->code);
    }

    protected function priceLabel(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->price === null ? null : ArcusCatalog::formatPrice($this->price));
    }

    protected function publicUrl(): Attribute
    {
        return Attribute::get(fn (): string => route('arcus.show', strtolower((string) $this->code)));
    }

    protected function rangeName(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->range?->name);
    }

    protected function instrumentName(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->instrument?->name);
    }

    protected function photoCount(): Attribute
    {
        return Attribute::get(fn (): int => $this->images()->count());
    }

    protected function mainImageUrl(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->images()->with('media')->first()?->media?->url());
    }
}
