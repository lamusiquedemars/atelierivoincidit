<?php

namespace App\Filament\Pages;

use App\Support\Modules;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class LucchiSpeeds extends Page
{
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedCalculator;

    protected static string | UnitEnum | null $navigationGroup = 'Catalogue';

    protected static ?string $navigationLabel = 'Vitesses Lucchi';

    protected static ?string $title = 'Tableau Vitesses Lucchi';

    protected static ?string $slug = 'arcus/speeds';

    protected static ?int $navigationSort = 30;

    protected string $view = 'filament.pages.lucchi-speeds';

    public static function shouldRegisterNavigation(): bool
    {
        return Modules::enabled('arcus');
    }

    public static function canAccess(): bool
    {
        return Modules::enabled('arcus') && parent::canAccess();
    }

    public function rows(): array
    {
        $rows = [];
        $headMassRatio = 0.0334;
        $headFactor = 0.73;
        $lucchiCoefficient = 1.061;

        for ($length = 700; $length <= 745; $length++) {
            $lengthMeters = $length / 1000;

            for ($frequency = 2900; $frequency <= 3500; $frequency += 10) {
                $rawSpeed = 2 * $lengthMeters * $frequency;
                $correctedSpeed = $rawSpeed * (1 + $headFactor * $headMassRatio);
                $lucchiSpeed = $correctedSpeed * $lucchiCoefficient;

                $rows[] = [
                    'length' => $length,
                    'frequency' => $frequency,
                    'raw_speed' => number_format($rawSpeed, 2, '.', ''),
                    'corrected_speed' => number_format($correctedSpeed, 2, '.', ''),
                    'lucchi_speed' => number_format($lucchiSpeed, 2, '.', ''),
                ];
            }
        }

        return $rows;
    }
}
