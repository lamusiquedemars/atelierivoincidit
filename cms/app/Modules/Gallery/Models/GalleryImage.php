<?php

namespace App\Modules\Gallery\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $fillable = [
        'title',
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

    public function getAltAttribute(): string
    {
        return $this->alt_text ?: $this->title;
    }
}
