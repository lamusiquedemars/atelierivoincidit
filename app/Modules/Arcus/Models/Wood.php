<?php

namespace App\Modules\Arcus\Models;

use Illuminate\Database\Eloquent\Model;

class Wood extends Model
{
    protected $connection = 'legacy';

    protected $table = 'wood';

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}
