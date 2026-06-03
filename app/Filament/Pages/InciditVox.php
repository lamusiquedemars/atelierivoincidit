<?php

namespace App\Filament\Pages;

use App\Support\Modules;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class InciditVox extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static string | UnitEnum | null $navigationGroup = 'Outils';

    protected static ?string $navigationLabel = 'Incidit Vox';

    protected static ?string $title = 'Incidit Vox';

    protected static ?string $slug = 'tools/incidit-vox';

    protected static ?int $navigationSort = 20;

    protected string $view = 'filament.pages.incidit-vox';

    public static function shouldRegisterNavigation(): bool
    {
        return Modules::enabled('arcus');
    }

    public static function canAccess(): bool
    {
        return Modules::enabled('arcus') && parent::canAccess();
    }
}
