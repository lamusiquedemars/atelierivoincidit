<?php

namespace App\Providers;

use App\Models\User;
use App\Modules\Media\Policies\MediaAssetPolicy;
use App\Modules\SiteSettings\Models\SiteSetting;
use App\Support\AtelierFoundationAccessGate;
use App\Support\AtelierMediaAccessGate;
use App\Support\AtelierModuleAvailability;
use App\Support\AtelierSitePresentation;
use App\Support\ContentPermissions;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Maracuja\SiteCore\Foundation\Contracts\FoundationAccessGate;
use Maracuja\SiteCore\Foundation\Contracts\ModuleAvailability;
use Maracuja\SiteCore\Foundation\Contracts\SitePresentation;
use Maracuja\SiteCore\Gallery\Models\Gallery;
use Maracuja\SiteCore\Gallery\Models\GalleryImage;
use Maracuja\SiteCore\Media\Contracts\MediaAccessGate;
use Maracuja\SiteCore\Media\Enums\MediaType;
use Maracuja\SiteCore\Media\Exceptions\MediaAssetInUseException;
use Maracuja\SiteCore\Media\Filament\Actions\MediaUploadAction;
use Maracuja\SiteCore\Media\Filament\Forms\Components\MaracujaRichEditor;
use Maracuja\SiteCore\Media\Filament\Forms\Components\MediaIdSelect;
use Maracuja\SiteCore\Media\Filament\Forms\Components\MediaPicker;
use Maracuja\SiteCore\Media\Filament\Tables\MediaPickerTable;
use Maracuja\SiteCore\Media\Models\MediaAsset;
use Maracuja\SiteCore\Media\Models\MediaUsage;
use Maracuja\SiteCore\Media\Services\ImageVariantService;
use Maracuja\SiteCore\Media\Services\MediaStorageService;
use Maracuja\SiteCore\Media\Services\VideoThumbnailService;
use Maracuja\SiteCore\Publication\Models\Article;
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

        Gate::before(function (User $user, string $ability, array $arguments): ?bool {
            $subject = $arguments[0] ?? null;
            $subjectClass = is_object($subject) ? $subject::class : $subject;

            if (! $user->isAdministrator() && in_array($subjectClass, [User::class, SiteSetting::class], true)) {
                return false;
            }

            if (! ContentPermissions::allows($user, $subjectClass)) {
                return false;
            }

            if (! $user->isAdministrator() && ! $user->canEditContent() && ! in_array($ability, ['viewAny', 'view'], true)) {
                return false;
            }

            if ($subjectClass && Gate::getPolicyFor($subjectClass)) {
                return null;
            }

            return true;
        });

        // Compatibility aliases are intentionally transitional. They make the
        // existing theme, Arcus bridge and historical commands execute the
        // shared Core implementation before the local source is removed.
        $this->registerSiteCoreCompatibilityAliases();
    }

    private function registerSiteCoreCompatibilityAliases(): void
    {
        foreach ([
            'App\\Modules\\Media\\Models\\MediaAsset' => MediaAsset::class,
            'App\\Modules\\Media\\Models\\MediaUsage' => MediaUsage::class,
            'App\\Modules\\Media\\Enums\\MediaType' => MediaType::class,
            'App\\Modules\\Media\\Exceptions\\MediaAssetInUseException' => MediaAssetInUseException::class,
            'App\\Modules\\Media\\Services\\ImageVariantService' => ImageVariantService::class,
            'App\\Modules\\Media\\Services\\MediaStorageService' => MediaStorageService::class,
            'App\\Modules\\Media\\Services\\VideoThumbnailService' => VideoThumbnailService::class,
            'App\\Modules\\Media\\Filament\\Actions\\MediaUploadAction' => MediaUploadAction::class,
            'App\\Modules\\Media\\Filament\\Forms\\Components\\MaracujaRichEditor' => MaracujaRichEditor::class,
            'App\\Modules\\Media\\Filament\\Forms\\Components\\MediaIdSelect' => MediaIdSelect::class,
            'App\\Modules\\Media\\Filament\\Forms\\Components\\MediaPicker' => MediaPicker::class,
            'App\\Modules\\Media\\Filament\\Tables\\MediaPickerTable' => MediaPickerTable::class,
            'App\\Modules\\Gallery\\Models\\Gallery' => Gallery::class,
            'App\\Modules\\Gallery\\Models\\GalleryImage' => GalleryImage::class,
            'App\\Modules\\Articles\\Models\\Article' => Article::class,
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
