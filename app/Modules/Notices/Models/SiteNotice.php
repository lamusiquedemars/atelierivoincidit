<?php

namespace App\Modules\Notices\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SiteNotice extends Model
{
    protected $fillable = [
        'title',
        'message',
        'link_label',
        'link_url',
        'placement',
        'tone',
        'is_published',
        'starts_at',
        'ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where(function (Builder $query): void {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function (Builder $query): void {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            });
    }

    public function scopeForPlacement(Builder $query, string $placement): Builder
    {
        return $query->where('placement', $placement);
    }
}
