<?php

namespace App\Filament\Resources\Arcus\Woods;

use App\Filament\Resources\Arcus\Woods\Pages\ManageWoods;
use App\Modules\Arcus\Models\Wood;
use App\Support\Modules;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class WoodResource extends Resource
{
    protected static ?string $model = Wood::class;

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedSquare3Stack3d;

    protected static ?string $navigationLabel = 'Bois';

    protected static string | UnitEnum | null $navigationGroup = 'Catalogue';

    protected static ?string $modelLabel = 'bois';

    protected static ?string $pluralModelLabel = 'bois';

    protected static ?int $navigationSort = 20;

    public static function shouldRegisterNavigation(): bool
    {
        return Modules::enabled('arcus');
    }

    public static function canAccess(): bool
    {
        return Modules::enabled('arcus') && parent::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
            ])
            ->defaultSort('name')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageWoods::route('/'),
        ];
    }
}
