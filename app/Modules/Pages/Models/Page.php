<?php

namespace App\Modules\Pages\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'template',
        'excerpt',
        'hero_title',
        'hero_subtitle',
        'hero_image_path',
        'body_blocks',
        'seo_title',
        'seo_description',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'body_blocks' => 'array',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }
}
