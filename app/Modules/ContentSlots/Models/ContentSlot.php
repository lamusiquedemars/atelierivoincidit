<?php

namespace App\Modules\ContentSlots\Models;

use Illuminate\Database\Eloquent\Model;

class ContentSlot extends Model
{
    protected $fillable = [
        'key',
        'label',
        'group',
        'type',
        'value',
        'help_text',
        'is_locked',
    ];

    protected function casts(): array
    {
        return [
            'is_locked' => 'boolean',
        ];
    }
}
