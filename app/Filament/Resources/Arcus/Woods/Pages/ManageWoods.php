<?php

namespace App\Filament\Resources\Arcus\Woods\Pages;

use App\Filament\Resources\Arcus\Woods\WoodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageWoods extends ManageRecords
{
    protected static string $resource = WoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Créer un bois'),
        ];
    }
}
