<?php

namespace App\Filament\Resources\ContentSlots;

use App\Filament\Resources\ContentSlots\Pages\ManageContentSlots;
use App\Modules\ContentSlots\Models\ContentSlot;
use App\Support\Modules;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContentSlotResource extends Resource
{
    protected static ?string $model = ContentSlot::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;

    protected static ?string $navigationLabel = 'Contenus courts';

    protected static UnitEnum|string|null $navigationGroup = 'Réglages';

    protected static ?string $modelLabel = 'contenu court';

    protected static ?string $pluralModelLabel = 'contenus courts';

    protected static ?int $navigationSort = 16;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('label')
                    ->label('Nom visible')
                    ->required()
                    ->maxLength(120),
                TextInput::make('key')
                    ->label('Clé technique')
                    ->required()
                    ->maxLength(120)
                    ->unique(ignoreRecord: true)
                    ->disabled(fn (?ContentSlot $record): bool => (bool) $record?->is_locked)
                    ->dehydrated(),
                TextInput::make('group')
                    ->label('Groupe')
                    ->required()
                    ->maxLength(80)
                    ->default('General'),
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'text' => 'Texte court',
                        'textarea' => 'Texte long',
                        'price' => 'Prix',
                        'date' => 'Date',
                    ])
                    ->required()
                    ->default('text'),
                Textarea::make('value')
                    ->label('Contenu')
                    ->rows(3)
                    ->maxLength(600)
                    ->columnSpanFull()
                    ->helperText('Zone courte et contrôlée. Le template décide où et comment ce contenu s’affiche.'),
                Textarea::make('help_text')
                    ->label('Aide admin')
                    ->rows(2)
                    ->columnSpanFull(),
                Toggle::make('is_locked')
                    ->label('Clé verrouillée')
                    ->helperText('Verrouiller les slots fournis par le starter pour éviter de casser les templates.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')
                    ->label('Groupe')
                    ->sortable(),
                TextColumn::make('label')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('key')
                    ->label('Clé')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('value')
                    ->label('Contenu')
                    ->limit(56)
                    ->searchable(),
                TextColumn::make('type')
                    ->label('Type'),
                IconColumn::make('is_locked')
                    ->label('Verrou')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageContentSlots::route('/'),
        ];
    }
}
