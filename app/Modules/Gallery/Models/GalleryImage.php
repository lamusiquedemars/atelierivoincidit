<?php

namespace App\Modules\Gallery\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class GalleryImage extends Model
{
    protected $fillable = [
        'title',
        'gallery_id',
        'caption',
        'credit',
        'image_path',
        'alt_text',
        'width',
        'height',
        'position',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'width' => 'integer',
            'height' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    public function getAltAttribute(): string
    {
        return $this->alt_text ?: $this->title;
    }

    public function getResolvedImageUrlAttribute(): string
    {
        return str_starts_with($this->image_path, 'http') || str_starts_with($this->image_path, '/')
            ? $this->image_path
            : Storage::disk('public')->url($this->image_path);
    }

    protected static function booted(): void
    {
        static::saving(function (self $image): void {
            if (! $image->image_path || (! $image->isDirty('image_path') && $image->width && $image->height)) {
                return;
            }

            $path = str_starts_with($image->image_path, '/')
                ? public_path(ltrim($image->image_path, '/'))
                : Storage::disk('public')->path($image->image_path);

            if (! is_file($path)) {
                return;
            }

            $dimensions = @getimagesize($path);

            if ($dimensions === false) {
                return;
            }

            $image->width = $dimensions[0];
            $image->height = $dimensions[1];
        });
    }
}
