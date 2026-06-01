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
use Filament\Schemas\Components\Html;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

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
                Html::make(fn (?GalleryImage $record): HtmlString => new HtmlString(
                    $record?->image_path
                        ? '<div style="display:grid;gap:.5rem"><img src="' . e($record->resolved_image_url) . '" alt="" style="max-width:360px;max-height:240px;object-fit:contain;border-radius:8px;background:#f3f4f6"><code style="font-size:.875rem">' . e($record->image_path) . '</code></div>'
                        : '<div style="color:#6b7280">Aucune image enregistrée.</div>'
                ))
                    ->columnSpanFull(),
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
                    ->helperText('Pour les images historiques /assets/images, l’aperçu ci-dessus fait foi. Téléverser ici seulement pour remplacer l’image.')
                    ->required(fn (?GalleryImage $record): bool => $record === null),
                TextInput::make('image_path_display')
                    ->label('Chemin enregistré')
                    ->formatStateUsing(fn (?GalleryImage $record): ?string => $record?->image_path)
                    ->helperText('Chemin utilisé par le front. Exemples : /assets/images/photo.jpeg ou gallery/photo.webp.')
                    ->disabled()
                    ->dehydrated(false)
                    ->copyable(),
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
                TextColumn::make('resolved_image_url')
                    ->label('Aperçu')
                    ->formatStateUsing(fn (?string $state, GalleryImage $record): HtmlString => new HtmlString(
                        $state
                            ? '<img src="' . e($state) . '" alt="' . e($record->alt) . '" style="width:96px;height:72px;object-fit:cover;border-radius:6px;background:#f3f4f6">'
                            : ''
                    ))
                    ->html(),
                TextColumn::make('title')
                    ->label('Titre')
                    ->searchable(),
                TextColumn::make('gallery.title')
                    ->label('Galerie')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('image_path')
                    ->label('Chemin')
                    ->limit(38)
                    ->copyable()
                    ->toggleable(),
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
