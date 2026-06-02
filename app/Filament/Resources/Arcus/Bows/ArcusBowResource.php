<?php

namespace App\Filament\Resources\Arcus\Bows;

use App\Filament\Resources\Arcus\Bows\Pages\ManageBows;
use App\Modules\Arcus\Models\Bow;
use App\Support\Modules;
use BackedEnum;
use UnitEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class ArcusBowResource extends Resource
{
    protected static ?string $model = Bow::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Archets';

    protected static UnitEnum|string|null $navigationGroup = 'Catalogue';

    protected static ?string $modelLabel = 'archet';

    protected static ?string $pluralModelLabel = 'archets';

    protected static ?int $navigationSort = 10;

    public static function shouldRegisterNavigation(): bool
    {
        return Modules::enabled('arcus');
    }

    public static function canAccess(): bool
    {
        return Modules::enabled('arcus') && parent::canAccess();
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identification')
                    // 3 colonnes ici restent acceptables : les champs sont courts.
                    ->columns(2)
                    ->schema([
                        TextInput::make('code')
                            ->label('Code')
                            ->required()
                            ->maxLength(40),

                        TextInput::make('name')
                            ->label('Nom atelier')
                            ->maxLength(100)
                            ->columnSpanFull(),

                        TextInput::make('price')
                            ->label('Prix public (€)')
                            ->numeric()
                            ->step('0.01')
                            ->formatStateUsing(fn ($state): ?string => self::centsToEuros($state))
                            ->dehydrateStateUsing(fn ($state): ?int => self::eurosToCents($state)),

                        TextInput::make('discount')
                            ->label('Remise %')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100),

                        Select::make('status')
                            ->label('Statut')
                            ->options(self::statusOptions())
                            ->default('available')
                            ->required(),

                        Toggle::make('active')
                            ->label('Visible sur le site')
                            ->default(true)
                            ->required(),
                    ]),

                Section::make('Photos')
                    ->columns(2)
                    ->schema([
                        TextInput::make('photo_directory_path')
                            ->label('Dossier attendu')
                            ->disabled()
                            ->dehydrated(false)
                            ->copyable(),

                        TextInput::make('main_image_url')
                            ->label('Image principale détectée')
                            ->disabled()
                            ->dehydrated(false)
                            ->copyable(),

                        Textarea::make("photo_public_paths_display")
                            ->label("Chemins publics des images")
                            ->formatStateUsing(fn (?Bow $record): string => $record ? implode("\n", $record->photo_public_paths) : "")
                            ->rows(6)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull()
                            ->helperText("Les photos sont détectées automatiquement depuis le dossier public de cet archet."),
                    ]),

                Section::make('Classification')
                    // Avant : columns(4).
                    // Trop serré pour des Select avec des valeurs longues.
                    // Ici : 1 colonne mobile, 2 colonnes tablette, 3 colonnes grand écran.
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                        'xl' => 2,
                    ])
                    ->schema([
                        Select::make('range_id')
                            ->label('Gamme')
                            ->options(fn () => self::legacyOptions('range'))
                            ->searchable(),

                        Select::make('instrument_id')
                            ->label('Instrument')
                            ->options(fn () => self::legacyOptions('instrument'))
                            ->searchable(),

                        Select::make('style_id')
                            ->label('Style')
                            ->options(fn () => self::legacyOptions('style'))
                            ->searchable(),

                        Select::make('shape_id')
                            ->label('Forme')
                            ->options(fn () => self::legacyOptions('shape'))
                            ->searchable(),

                        Select::make('size_id')
                            ->label('Taille')
                            ->options(fn () => self::legacyOptions('size'))
                            ->searchable(),

                        Select::make('wood_id')
                            ->label('Bois')
                            ->options(fn () => self::legacyOptions('wood'))
                            ->searchable(),

                        Select::make('origin_id')
                            ->label('Origine')
                            ->options(fn () => self::legacyOptions('origin'))
                            ->searchable(),

                        Select::make('color_id')
                            ->label('Couleur')
                            ->options(fn () => self::legacyOptions('color'))
                            ->searchable(),
                    ]),

                Section::make('Montage')
                    // Avant : columns(3).
                    // Les libellés sont longs, donc 2 colonnes max donnent un admin plus confortable.
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                    ])
                    ->schema([
                        Select::make('button_material_id')
                            ->label('Matériau du bouton')
                            ->options(fn () => self::legacyOptions('material'))
                            ->searchable(),

                        Select::make('frog_material_id')
                            ->label('Matériau de la hausse')
                            ->options(fn () => self::legacyOptions('material'))
                            ->searchable(),

                        Select::make('slide_material_id')
                            ->label('Matériau de la coulisse')
                            ->options(fn () => self::legacyOptions('material'))
                            ->searchable(),

                        Select::make('tip_material_id')
                            ->label('Matériau de la pointe')
                            ->options(fn () => self::legacyOptions('material'))
                            ->searchable(),

                        Select::make('garnish_id')
                            ->label('Garniture')
                            ->options(fn () => self::legacyOptions('garnish'))
                            ->searchable(),
                    ]),

                Section::make('Mesures physiques')
                    // Avant : columns(5).
                    // 5 colonnes crée des champs minuscules.
                    // 3 colonnes suffisent et restent efficaces pour de la saisie chiffrée.
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                        'xl' => 3,
                    ])
                    ->schema([
                        self::measureInput('stick_length', 'Longueur baguette (mm)', '0.1'),
                        self::measureInput('total_length', 'Longueur totale (mm)', '0.1'),
                        self::measureInput('stick_weight', 'Poids baguette (g)', '0.01'),
                        self::measureInput('total_weight', 'Poids total (g)', '0.01'),
                        self::measureInput('balance_point', 'Équilibre (mm)', '0.1'),
                        self::measureInput('density', 'Densité (kg/m³)', '0.1'),
                        self::measureInput('speed', 'Vitesse du son (m/s)', '0.1'),
                        self::measureInput('elasticity', 'Élasticité (GPa)', '0.1'),
                        self::measureInput('frequency', 'Fréquence (Hz)', '0.1'),
                        self::measureInput('damping', 'Amortissement δ', '0.0001'),
                    ]),

                Section::make('Caractère de jeu')
                    // Avant : columns(4).
                    // Les Select de caractère de jeu ont besoin d’air.
                    ->columns([
                        'default' => 1,
                        'md' => 2,
                        'xl' => 2,
                    ])
                    ->schema([
                        Select::make('flexibility_id')
                            ->label('Flexibilité')
                            ->options(fn () => self::qualityOptions('flexibilité'))
                            ->searchable(),

                        Select::make('responsiveness_id')
                            ->label('Réactivité')
                            ->options(fn () => self::qualityOptions('réactivité'))
                            ->searchable(),

                        Select::make('handling_id')
                            ->label('Maniabilité')
                            ->options(fn () => self::qualityOptions('maniabilité'))
                            ->searchable(),

                        Select::make('natural_pressure_id')
                            ->label('Pression naturelle')
                            ->options(fn () => self::qualityOptions('pression naturelle'))
                            ->searchable(),

                        Select::make('projection_id')
                            ->label('Projection')
                            ->options(fn () => self::qualityOptions('projection'))
                            ->searchable(),

                        Select::make('sustain_id')
                            ->label('Sustain')
                            ->options(fn () => self::qualityOptions('sustain'))
                            ->searchable(),

                        Select::make('tone_id')
                            ->label('Timbre')
                            ->options(fn () => self::qualityOptions('timbre'))
                            ->searchable(),

                        Select::make('articulation_id')
                            ->label('Articulation')
                            ->options(fn () => self::qualityOptions('articulation'))
                            ->searchable(),
                    ]),

                Section::make('Texte interne / affichage')
                    // Pas besoin de colonnes ici : les Textarea prennent toute la largeur.
                    ->schema([
                        Textarea::make('short_trait')
                            ->label('Trait court')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('notes')
                            ->label('Notes de l’archetier')
                            ->rows(8)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make("main_image_url")
                    ->label("Photo")
                    ->formatStateUsing(fn (?string $state): HtmlString => new HtmlString(
                        $state
                            ? "<img src=\"" . e($state) . "\" alt=\"\" style=\"width:64px;height:48px;object-fit:cover;border-radius:6px;background:#f3f4f6\">"
                            : "<span style=\"color:#9ca3af\">Aucune</span>"
                    ))
                    ->html()
                    ->toggleable(),

                TextColumn::make('display_title')
                    ->label('Archet')
                    ->searchable(['name', 'code']),

                TextColumn::make('range_name')
                    ->label('Gamme')
                    ->sortable(false),

                TextColumn::make('instrument_name')
                    ->label('Instrument')
                    ->sortable(false)
                    ->toggleable(),

                TextColumn::make('price_label')
                    ->label('Prix'),

                TextColumn::make('photo_count')
                    ->label('Photos')
                    ->numeric()
                    ->sortable(false),

                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'available' => 'Disponible',
                        'unavailable' => 'Indisponible',
                        'sold' => 'Vendu',
                        default => $state,
                    }),

                IconColumn::make('active')
                    ->label('Publié')
                    ->boolean(),

                TextColumn::make('public_url')
                    ->label('Page publique')
                    ->url(fn (Bow $record): string => $record->public_url)
                    ->openUrlInNewTab()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options(self::statusOptions()),

                TernaryFilter::make('active')
                    ->label('Publié'),
            ])
            ->recordActions([
                EditAction::make()
                    ->modalWidth(Width::SixExtraLarge),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageBows::route('/'),
        ];
    }

    protected static function statusOptions(): array
    {
        return [
            'available' => 'Disponible',
            'unavailable' => 'Indisponible',
            'sold' => 'Vendu',
        ];
    }

    protected static function legacyOptions(string $table): array
    {
        return DB::connection('legacy')
            ->table($table)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }

    protected static function qualityOptions(string $type): array
    {
        return DB::connection('legacy')
            ->table('quality')
            ->where('type', $type)
            ->orderBy('id')
            ->pluck('name', 'id')
            ->all();
    }

    protected static function measureInput(string $name, string $label, string $step): TextInput
    {
        return TextInput::make($name)
            ->label($label)
            ->numeric()
            ->step($step)
            ->dehydrateStateUsing(fn ($state): ?float => self::nullableMeasure($state));
    }

    protected static function centsToEuros($state): ?string
    {
        if ($state === null || $state === '') {
            return null;
        }

        return rtrim(rtrim(number_format(((int) $state) / 100, 2, '.', ''), '0'), '.');
    }

    protected static function eurosToCents($state): ?int
    {
        if ($state === null || trim((string) $state) === '') {
            return null;
        }

        return (int) round(((float) str_replace(',', '.', (string) $state)) * 100);
    }

    protected static function nullableMeasure($state): ?float
    {
        if ($state === null || trim((string) $state) === '') {
            return null;
        }

        $value = (float) str_replace(',', '.', (string) $state);

        return $value === 0.0 ? null : $value;
    }
}
