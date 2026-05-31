<?php

namespace App\Filament\Resources\Arcus\Bows\Pages;

use App\Filament\Resources\Arcus\Bows\ArcusBowResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Width;

class ManageBows extends ManageRecords
{
    protected static string $resource = ArcusBowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalWidth(Width::SixExtraLarge),
        ];
    }
}
