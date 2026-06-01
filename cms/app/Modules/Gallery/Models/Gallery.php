<?php

namespace App\Modules\Gallery\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Gallery extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'intro',
        'position',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function images(): HasMany
    {
        return $this->hasMany(GalleryImage::class)->orderBy('position');
    }

    protected static function booted(): void
    {
        static::saving(function (self $gallery): void {
            if (! $gallery->slug) {
                $gallery->slug = Str::slug($gallery->title);
            }
        });
    }
}
