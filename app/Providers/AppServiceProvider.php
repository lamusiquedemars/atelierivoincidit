<?php

namespace App\Providers;

use App\Modules\Media\Policies\MediaAssetPolicy;
use App\Support\AtelierFoundationAccessGate;
use App\Support\AtelierMediaAccessGate;
use App\Support\AtelierModuleAvailability;
use App\Support\AtelierSitePresentation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Maracuja\SiteCore\Foundation\Contracts\FoundationAccessGate;
use Maracuja\SiteCore\Foundation\Contracts\ModuleAvailability;
use Maracuja\SiteCore\Foundation\Contracts\SitePresentation;
use Maracuja\SiteCore\Media\Contracts\MediaAccessGate;
use Maracuja\SiteCore\Media\Models\MediaAsset;
use RuntimeException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(FoundationAccessGate::class, AtelierFoundationAccessGate::class);
        $this->app->singleton(ModuleAvailability::class, AtelierModuleAvailability::class);
        $this->app->singleton(SitePresentation::class, AtelierSitePresentation::class);
        $this->app->singleton(MediaAccessGate::class, AtelierMediaAccessGate::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->guardTestingDatabase();

        $this->loadMigrationsFrom(app_path('Modules/Media/database/migrations'));

        Gate::policy(MediaAsset::class, MediaAssetPolicy::class);

        // Compatibility aliases are intentionally transitional. They make the
        // existing theme, Arcus bridge and historical commands execute the
        // shared Core implementation before the local source is removed.
        $this->registerSiteCoreCompatibilityAliases();
    }

    private function registerSiteCoreCompatibilityAliases(): void
    {
        foreach ([
            'App\\Modules\\Media\\Models\\MediaAsset' => MediaAsset::class,
            'App\\Modules\\Media\\Models\\MediaUsage' => \Maracuja\SiteCore\Media\Models\MediaUsage::class,
            'App\\Modules\\Media\\Enums\\MediaType' => \Maracuja\SiteCore\Media\Enums\MediaType::class,
            'App\\Modules\\Media\\Exceptions\\MediaAssetInUseException' => \Maracuja\SiteCore\Media\Exceptions\MediaAssetInUseException::class,
            'App\\Modules\\Media\\Services\\ImageVariantService' => \Maracuja\SiteCore\Media\Services\ImageVariantService::class,
            'App\\Modules\\Media\\Services\\MediaStorageService' => \Maracuja\SiteCore\Media\Services\MediaStorageService::class,
            'App\\Modules\\Media\\Services\\VideoThumbnailService' => \Maracuja\SiteCore\Media\Services\VideoThumbnailService::class,
            'App\\Modules\\Media\\Filament\\Actions\\MediaUploadAction' => \Maracuja\SiteCore\Media\Filament\Actions\MediaUploadAction::class,
            'App\\Modules\\Media\\Filament\\Forms\\Components\\MaracujaRichEditor' => \Maracuja\SiteCore\Media\Filament\Forms\Components\MaracujaRichEditor::class,
            'App\\Modules\\Media\\Filament\\Forms\\Components\\MediaIdSelect' => \Maracuja\SiteCore\Media\Filament\Forms\Components\MediaIdSelect::class,
            'App\\Modules\\Media\\Filament\\Forms\\Components\\MediaPicker' => \Maracuja\SiteCore\Media\Filament\Forms\Components\MediaPicker::class,
            'App\\Modules\\Media\\Filament\\Tables\\MediaPickerTable' => \Maracuja\SiteCore\Media\Filament\Tables\MediaPickerTable::class,
            'App\\Modules\\Gallery\\Models\\Gallery' => \Maracuja\SiteCore\Gallery\Models\Gallery::class,
            'App\\Modules\\Gallery\\Models\\GalleryImage' => \Maracuja\SiteCore\Gallery\Models\GalleryImage::class,
        ] as $legacy => $core) {
            if (! class_exists($legacy, false)) {
                class_alias($core, $legacy);
            }
        }
    }

    private function guardTestingDatabase(): void
    {
        if (! $this->app->environment('testing')) {
            return;
        }

        $connection = (string) config('database.default');
        $database = (string) config("database.connections.{$connection}.database");

        if ($database === ':memory:' || str_ends_with($database, '_testing')) {
            return;
        }

        throw new RuntimeException(
            "Tests blocked: database [{$database}] is not a dedicated testing database. "
            .'Use a database name ending in [_testing].'
        );
    }
}
