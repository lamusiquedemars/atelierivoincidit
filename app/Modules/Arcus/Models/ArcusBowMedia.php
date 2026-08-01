<?php

namespace App\Modules\Arcus\Models;

use App\Modules\Media\Concerns\TracksMediaUsages;
use App\Modules\Media\Models\MediaAsset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArcusBowMedia extends Model
{
    use TracksMediaUsages;

    protected $table = 'arcus_bow_media';

    protected $fillable = [
        'media_asset_id',
        'position',
        'caption',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $image): void {
            if ($image->position === null && $image->arcus_bow_id) {
                $image->position = (int) static::query()
                    ->where('arcus_bow_id', $image->arcus_bow_id)
                    ->max('position') + 1;
            }
        });
    }

    public function bow(): BelongsTo
    {
        return $this->belongsTo(Bow::class, 'arcus_bow_id');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(MediaAsset::class, 'media_asset_id');
    }

    protected function mediaUsageReferences(): array
    {
        return [[
            'media_asset_id' => $this->media_asset_id,
            'field' => 'gallery',
            'context' => (string) $this->position,
        ]];
    }
}
