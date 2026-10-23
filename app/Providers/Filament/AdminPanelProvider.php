<?php

namespace App\Providers\Filament;

use App\Support\Modules;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Maracuja\SiteCore\Gallery\Filament\Resources\Galleries\GalleryResource;
use Maracuja\SiteCore\Media\Filament\Resources\MediaAssets\MediaAssetResource;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel = $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);

        // Gallery is now supplied by Site Core. The historical resource is
        // deliberately excluded so Filament exposes one route and one UI.
        foreach (glob(app_path('Filament/Resources/*'), GLOB_ONLYDIR) ?: [] as $path) {
            $resource = basename($path);

            if ($resource === 'Galleries') {
                continue;
            }

            $panel->discoverResources(
                in: $path,
                for: 'App\\Filament\\Resources\\'.$resource,
            );
        }

        $panel->resources(array_filter([
            MediaAssetResource::class,
            Modules::enabled('gallery') ? GalleryResource::class : null,
        ]));

        return $panel;
    }
}
