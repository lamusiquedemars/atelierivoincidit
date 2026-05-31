<?php

namespace App\Modules\Articles\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'image_path',
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

    public function scopeVisible(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where(function (Builder $query): void {
                $query->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeForListing(Builder $query): Builder
    {
        return $query
            ->visible()
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');
    }

    public function publicExcerpt(): string
    {
        if (filled($this->excerpt)) {
            return $this->excerpt;
        }

        $firstText = collect($this->body_blocks ?? [])
            ->first(fn (array $block): bool => ($block['type'] ?? null) === 'rich_text' && filled($block['text'] ?? null));

        return str($firstText['text'] ?? '')
            ->stripTags()
            ->squish()
            ->limit(180)
            ->toString();
    }
}
