<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Modules\SiteSettings\Models\SiteSetting;
use App\Support\Modules;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MaracujaDoctorCommand extends Command
{
    protected $signature = 'maracuja:doctor {--production : Run stricter checks before public delivery}';

    protected $description = 'Check whether the Maracuja CMS installation is ready for delivery.';

    /** @var array<int, array{status: string, check: string, detail: string}> */
    private array $results = [];

    public function handle(): int
    {
        $production = (bool) $this->option('production');

        $this->info('Maracuja CMS Doctor');
        $this->newLine();

        $this->checkAppConfiguration($production);
        $this->checkOfferConfiguration();
        $this->checkDatabaseContent();
        $this->checkStorage();
        $this->checkSeo($production);

        $this->table(['Status', 'Check', 'Detail'], $this->results);

        if ($this->hasFailures()) {
            $this->newLine();
            $this->error('Installation needs attention before delivery.');

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Installation looks healthy.');

        return self::SUCCESS;
    }

    private function checkAppConfiguration(bool $production): void
    {
        $this->record(
            filled(config('app.key')) ? 'ok' : 'fail',
            'APP_KEY',
            filled(config('app.key')) ? 'Application key is configured.' : 'Run php artisan key:generate.'
        );

        $url = (string) config('app.url');
        $isLocalUrl = str_contains($url, 'localhost') || str_contains($url, '127.0.0.1');

        $this->record(
            $production && $isLocalUrl ? 'fail' : 'ok',
            'APP_URL',
            $production && $isLocalUrl ? 'Production URL still points to local.' : "Current URL: {$url}"
        );
    }

    private function checkOfferConfiguration(): void
    {
        $offer = Modules::offer();
        $offers = array_keys((array) config('maracuja.offers', []));

        $this->record(
            in_array($offer, $offers, true) ? 'ok' : 'fail',
            'Offer profile',
            in_array($offer, $offers, true)
                ? "Active offer: {$offer}."
                : 'Unknown offer. Expected one of: '.implode(', ', $offers).'.'
        );

        $enabledModules = collect((array) config('maracuja.modules', []))
            ->keys()
            ->filter(fn (string $module): bool => Modules::enabled($module))
            ->values()
            ->all();

        $this->record(
            count($enabledModules) > 0 ? 'ok' : 'fail',
            'Enabled modules',
            count($enabledModules) > 0 ? implode(', ', $enabledModules) : 'No module is enabled.'
        );
    }

    private function checkDatabaseContent(): void
    {
        try {
            DB::connection()->getPdo();
        } catch (\Throwable $exception) {
            $this->record('fail', 'Database connection', $exception->getMessage());

            return;
        }

        $this->record('ok', 'Database connection', 'Connection is available.');

        if (! Schema::hasTable('users')) {
            $this->record('fail', 'Migrations', 'Users table is missing. Run migrations.');

            return;
        }

        $this->record('ok', 'Migrations', 'Core tables are available.');

        $this->record(
            User::query()->where('is_admin', true)->exists() ? 'ok' : 'fail',
            'Admin account',
            User::query()->where('is_admin', true)->exists()
                ? 'At least one admin account exists.'
                : 'Create an admin user before delivery.'
        );

        if (Modules::enabled('site_settings')) {
            $settings = SiteSetting::query()->first();

            $this->record(
                $settings !== null ? 'ok' : 'fail',
                'Site settings',
                $settings !== null ? "Site name: {$settings->site_name}." : 'Missing site settings record.'
            );

            if (Modules::enabled('contact')) {
                $this->record(
                    $settings && filled($settings->contact_email) ? 'ok' : 'warn',
                    'Contact email',
                    $settings && filled($settings->contact_email)
                        ? "Contact email: {$settings->contact_email}."
                        : 'Contact module is enabled but contact email is empty.'
                );
            }
        }
    }

    private function checkStorage(): void
    {
        $this->record(
            is_link(public_path('storage')) || file_exists(public_path('storage')) ? 'ok' : 'warn',
            'Public storage',
            is_link(public_path('storage')) || file_exists(public_path('storage'))
                ? 'public/storage exists.'
                : 'Run php artisan storage:link if uploaded media must be public.'
        );
    }

    private function checkSeo(bool $production): void
    {
        $indexable = (bool) config('maracuja.seo.indexable');

        $this->record(
            $production && ! $indexable ? 'fail' : 'ok',
            'SEO indexing',
            $indexable
                ? 'Site can be indexed.'
                : 'Site is configured as noindex. Good for lab/staging, not for public production.'
        );
    }

    private function record(string $status, string $check, string $detail): void
    {
        $this->results[] = [
            'status' => strtoupper($status),
            'check' => $check,
            'detail' => $detail,
        ];
    }

    private function hasFailures(): bool
    {
        return collect($this->results)
            ->contains(fn (array $result): bool => $result['status'] === 'FAIL');
    }
}
