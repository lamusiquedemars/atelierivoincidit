<?php

namespace App\Filament\Resources\Galleries;

use App\Filament\Resources\Galleries\Pages\ManageGalleries;
use App\Modules\Gallery\Models\Gallery;
use App\Support\Modules;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $navigationLabel = 'Galeries';
    protected static string|UnitEnum|null $navigationGroup = 'Galerie';

    protected static ?string $modelLabel = 'galerie';

    protected static ?string $pluralModelLabel = 'galeries';

    protected static ?int $navigationSort = 30;

    public static function shouldRegisterNavigation(): bool
    {
        return Modules::enabled('gallery');
    }

    public static function canAccess(): bool
    {
        return Modules::enabled('gallery') && parent::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Nom')
                    ->required(),
                TextInput::make('slug')
                    ->label('Code')
                    ->helperText('Identifiant technique utilise par les templates, par exemple home ou atelier-home.')
                    ->required()
                    ->unique(ignoreRecord: true),
                Textarea::make('intro')
                    ->label('Introduction')
                    ->columnSpanFull(),
                TextInput::make('position')
                    ->label('Ordre')
                    ->numeric()
                    ->default(0)
                    ->required(),
                Toggle::make('is_published')
                    ->label('Publié')
                    ->required()
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('Code')
                    ->searchable(),
                TextColumn::make('images_count')
                    ->counts('images')
                    ->label('Photos')
                    ->sortable(),
                TextColumn::make('position')
                    ->label('Ordre')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('Publié')
                    ->boolean(),
            ])
            ->defaultSort('position')
            ->reorderable('position')
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
            'index' => ManageGalleries::route('/'),
        ];
    }
}
