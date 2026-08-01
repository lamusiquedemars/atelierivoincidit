<?php

namespace App\Modules\Arcus\Models;

use Illuminate\Database\Eloquent\Model;

class ArcusTerm extends Model
{
    protected $fillable = [
        'type',
        'legacy_id',
        'name',
        'group',
        'slug',
        'description',
    ];
}
