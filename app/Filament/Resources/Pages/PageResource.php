<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Resources\Pages\Pages\ManagePages;
use App\Modules\Pages\Models\Page;
use App\Support\Modules;
use BackedEnum;
use UnitEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
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
use Filament\Tables\Table;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Pages';

    protected static UnitEnum|string|null $navigationGroup = 'Contenus';

    protected static ?string $modelLabel = 'page';

    protected static ?string $pluralModelLabel = 'pages';

    protected static ?int $navigationSort = 10;

    public static function shouldRegisterNavigation(): bool
    {
        return Modules::enabled('pages') && Modules::developerToolEnabled('pages_admin');
    }

    public static function canAccess(): bool
    {
        return Modules::enabled('pages')
            && Modules::developerToolEnabled('pages_admin')
            && parent::canAccess();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Titre')
                    ->required()
                    ->helperText('Nom de page. Pour les pages système, la structure reste dans le template.'),
                TextInput::make('slug')
                    ->label('Slug')
                    ->disabled(fn (?Page $record): bool => in_array($record?->slug, ['accueil', 'services'], true))
                    ->dehydrated(fn (?Page $record): bool => ! in_array($record?->slug, ['accueil', 'services'], true))
                    ->required(),
                Select::make('template')
                    ->label('Template')
                    ->options([
                        'default' => 'Page simple',
                        'landing' => 'Page avec sections',
                        'services' => 'Services / offres',
                    ])
                    ->helperText('Le template définit la structure Blade de la page. Pour les pages système, ne le change pas sauf intention claire.')
                    ->disabled(fn (?Page $record): bool => in_array($record?->slug, ['accueil', 'services'], true))
                    ->dehydrated(fn (?Page $record): bool => ! in_array($record?->slug, ['accueil', 'services'], true))
                    ->required()
                    ->default('default'),
                Textarea::make('excerpt')
                    ->label('Résumé')
                    ->columnSpanFull(),
                TextInput::make('hero_title')
                    ->label('Titre hero'),
                Textarea::make('hero_subtitle')
                    ->label('Sous-titre hero')
                    ->columnSpanFull(),
                FileUpload::make('hero_image_path')
                    ->label('Image hero')
                    ->directory('pages')
                    ->image(),
                TextInput::make('seo_title')
                    ->label('Titre SEO'),
                Textarea::make('seo_description')
                    ->label('Description SEO')
                    ->columnSpanFull(),
                Toggle::make('is_published')
                    ->label('Publié')
                    ->required(),
                DateTimePicker::make('published_at')
                    ->label('Date de publication'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Titre')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('template')
                    ->searchable(),
                TextColumn::make('hero_title')
                    ->searchable(),
                ImageColumn::make('hero_image_path'),
                TextColumn::make('seo_title')
                    ->searchable(),
                IconColumn::make('is_published')
                    ->label('Publié')
                    ->boolean(),
                TextColumn::make('published_at')
                    ->label('Publication')
                    ->dateTime()
                    ->sortable(),
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
                //
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
            'index' => ManagePages::route('/'),
        ];
    }
}
