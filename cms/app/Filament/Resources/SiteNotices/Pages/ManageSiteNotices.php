<?php

namespace App\Filament\Resources\SiteNotices\Pages;

use App\Filament\Resources\SiteNotices\SiteNoticeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSiteNotices extends ManageRecords
{
    protected static string $resource = SiteNoticeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
