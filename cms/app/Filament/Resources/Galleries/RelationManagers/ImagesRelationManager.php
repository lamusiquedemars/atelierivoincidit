<?php

namespace App\Filament\Resources\Galleries\RelationManagers;

use App\Modules\Gallery\Models\GalleryImage;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected static ?string $title = 'Photos';

    public function form(Schema $schema): Schema
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
                    ->required()
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
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
                TextColumn::make('caption')
                    ->label('Légende')
                    ->limit(50)
                    ->toggleable(),
                TextColumn::make('image_path')
                    ->label('Chemin')
                    ->limit(38)
                    ->copyable()
                    ->toggleable(),
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
            ->headerActions([
                CreateAction::make()
                    ->label('Ajouter une photo'),
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
}
