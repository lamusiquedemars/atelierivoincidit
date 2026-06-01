<?php

namespace App\Filament\Resources\GalleryImages;

use App\Filament\Resources\GalleryImages\Pages\ManageGalleryImages;
use App\Modules\Gallery\Models\Gallery;
use App\Modules\Gallery\Models\GalleryImage;
use App\Support\Modules;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GalleryImageResource extends Resource
{
    protected static ?string $model = GalleryImage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Photos';
    protected static string|UnitEnum|null $navigationGroup = 'Galerie';

    protected static ?string $modelLabel = 'image';

    protected static ?string $pluralModelLabel = 'photos';

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
                    ->label('Titre')
                    ->required(),
                Select::make('gallery_id')
                    ->label('Galerie')
                    ->options(fn () => Gallery::query()->orderBy('position')->pluck('title', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                Textarea::make('caption')
                    ->label('Légende')
                    ->columnSpanFull(),
                TextInput::make('alt_text')
                    ->label('Texte alternatif')
                    ->helperText('Décrire l’image si elle apporte une information. Laisser vide si le titre suffit.'),
                TextInput::make('credit')
                    ->label('Crédit photo'),
                FileUpload::make('image_path')
                    ->label('Image')
                    ->directory('gallery')
                    ->image()
                    ->imagePreviewHeight('220')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->required(),
                TextInput::make('position')
                    ->label('Ordre')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_published')
                    ->label('Publié')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Titre')
                    ->searchable(),
                ImageColumn::make('resolved_image_url')
                    ->label('Aperçu')
                    ->checkFileExistence(false)
                    ->imageHeight(72)
                    ->imageWidth(96),
                TextColumn::make('gallery.title')
                    ->label('Galerie')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('alt_text')
                    ->label('Alt')
                    ->limit(32)
                    ->toggleable(),
                TextColumn::make('position')
                    ->label('Ordre')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_published')
                    ->label('Publié')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('gallery_id')
                    ->label('Galerie')
                    ->relationship('gallery', 'title'),
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
            'index' => ManageGalleryImages::route('/'),
        ];
    }
}
