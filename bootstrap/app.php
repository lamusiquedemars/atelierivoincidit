<?php

use App\Console\Commands\MaracujaDatabaseBackupCommand;
use App\Console\Commands\MaracujaDoctorCommand;
use App\Console\Commands\MaracujaMediaAuditCommand;
use App\Console\Commands\MaracujaMediaMigrateCommand;
use App\Console\Commands\MaracujaMediaThumbnailsCommand;
use App\Console\Commands\RetryCremonaDeliveriesCommand;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// LWS deployments keep Composer dependencies in place between file transfers.
// Register shared packages explicitly so an updated package remains available
// even before Composer's optimized metadata is regenerated.
spl_autoload_register(static function (string $class): void {
    $prefixes = [
        'Maracuja\\SiteCore\\Contact\\' => 'site-core-contact',
        'Maracuja\\SiteCore\\Foundation\\' => 'site-core-foundation',
        'Maracuja\\SiteCore\\Gallery\\' => 'site-core-gallery',
        'Maracuja\\SiteCore\\Media\\' => 'site-core-media',
    ];

    foreach ($prefixes as $prefix => $package) {
        if (! str_starts_with($class, $prefix)) {
            continue;
        }

        $path = dirname(__DIR__).'/vendor/maracuja-digital/'.$package.'/src/'
            .str_replace('\\', '/', substr($class, strlen($prefix))).'.php';

        if (is_file($path)) {
            require_once $path;
        }

        return;
    }
}, true, true);

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withCommands([
        MaracujaDoctorCommand::class,
        MaracujaDatabaseBackupCommand::class,
        MaracujaMediaAuditCommand::class,
        MaracujaMediaMigrateCommand::class,
        MaracujaMediaThumbnailsCommand::class,
        RetryCremonaDeliveriesCommand::class,
    ])
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
