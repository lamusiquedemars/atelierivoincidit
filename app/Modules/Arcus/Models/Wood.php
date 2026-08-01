<?php

namespace App\Modules\Arcus\Models;

use Illuminate\Database\Eloquent\Builder;

class Wood extends ArcusTerm
{
    protected static function booted(): void
    {
        static::addGlobalScope('wood', fn (Builder $query) => $query->where('type', 'wood'));
        static::creating(fn (self $wood) => $wood->type = 'wood');
    }
}
