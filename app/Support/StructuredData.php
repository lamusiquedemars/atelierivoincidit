<?php

namespace App\Support;

use App\Modules\SiteSettings\Models\SiteSetting;

class StructuredData
{
    /** @return array<string, mixed> */
    public static function siteGraph(SiteSetting $settings): array
    {
        $baseUrl = rtrim((string) config('app.url'), '/');
        $organizationId = $baseUrl.'/#organization';
        $personId = $baseUrl.'/#ivo-correia-de-melo';
        $sameAs = collect($settings->social_links ?? [])
            ->flatten()
            ->filter(fn (mixed $url): bool => is_string($url) && filter_var($url, FILTER_VALIDATE_URL) !== false)
            ->values()
            ->all();

        $organization = array_filter([
            '@type' => 'LocalBusiness',
            '@id' => $organizationId,
            'name' => 'Atelier Ivo Incidit',
            'url' => $baseUrl,
            'email' => $settings->contact_email ?: null,
            'logo' => $settings->logoUrl() ? Seo::absoluteUrl($settings->logoUrl()) : null,
            'image' => $settings->defaultOgImageUrl() ? Seo::absoluteUrl($settings->defaultOgImageUrl()) : null,
            'founder' => ['@id' => $personId],
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Collonges-au-Mont-d’Or',
            ],
            'areaServed' => [
                '@type' => 'City',
                'name' => 'Lyon',
            ],
            'sameAs' => $sameAs !== [] ? $sameAs : null,
        ], fn (mixed $value): bool => $value !== null);

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                $organization,
                [
                    '@type' => 'Person',
                    '@id' => $personId,
                    'name' => 'Ivo Correia de Melo',
                    'jobTitle' => 'Archetier',
                    'url' => $baseUrl.'/archetier',
                    'worksFor' => ['@id' => $organizationId],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => $baseUrl.'/#website',
                    'url' => $baseUrl,
                    'name' => 'Atelier Ivo Incidit',
                    'inLanguage' => 'fr-FR',
                    'publisher' => ['@id' => $organizationId],
                ],
            ],
        ];
    }

    /** @param array<string, mixed> $data */
    public static function json(array $data): string
    {
        return (string) json_encode(
            $data,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT,
        );
    }
}
