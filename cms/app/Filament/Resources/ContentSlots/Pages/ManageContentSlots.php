<?php

namespace App\Filament\Resources\ContentSlots\Pages;

use App\Filament\Resources\ContentSlots\ContentSlotResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageContentSlots extends ManageRecords
{
    protected static string $resource = ContentSlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
