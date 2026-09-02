<!doctype html>
<html lang="fr">
<head>
    @php
        $seo = \App\Support\Seo::make($settings, [
            'title' => $seoTitle ?? null,
            'description' => $seoDescription ?? null,
            'image' => $seoImage ?? null,
            'type' => $seoType ?? null,
            'canonical' => $canonical ?? null,
            'robots' => $seoRobots ?? null,
        ]);

        $clientTheme = config('maracuja.client_theme');
        $isIvoIncidit = $clientTheme === 'ivo-incidit';
        $configuredLogo = $settings->logoUrl();
        $legacyIvoLogos = [
            '/assets/images/blason-ivo-incidit2.png',
            '/assets/images/blason-header.png',
        ];
        $brandLogo = $isIvoIncidit && in_array($configuredLogo, $legacyIvoLogos, true)
            ? '/assets/images/logo-ivo-incidit-header-v2.webp'
            : ($configuredLogo ?: ($isIvoIncidit ? '/assets/images/logo-ivo-incidit-header-v2.webp' : null));
        $favicon = $settings->faviconUrl();
        $gtmContainerId = config('services.google_tag_manager.container_id');
    @endphp

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    <meta name="robots" content="{{ $seo['robots'] }}">
    <link rel="canonical" href="{{ $seo['canonical'] }}">

    <meta property="og:site_name" content="{{ $seo['site_name'] }}">
    <meta property="og:title" content="{{ $seo['title'] }}">
    <meta property="og:description" content="{{ $seo['description'] }}">
    <meta property="og:type" content="{{ $seo['type'] }}">
    <meta property="og:url" content="{{ $seo['canonical'] }}">
    @if ($seo['image'])
        <meta property="og:image" content="{{ $seo['image'] }}">
        <meta name="twitter:card" content="summary_large_image">
    @else
        <meta name="twitter:card" content="summary">
    @endif
    <meta name="twitter:title" content="{{ $seo['title'] }}">
    <meta name="twitter:description" content="{{ $seo['description'] }}">
    @if ($seo['image'])
        <meta name="twitter:image" content="{{ $seo['image'] }}">
    @endif

    @if ($isIvoIncidit)
        <script type="application/ld+json">{!! \App\Support\StructuredData::json(\App\Support\StructuredData::siteGraph($settings)) !!}</script>
    @endif
    @stack('structured-data')

    @if ($favicon)
        <link rel="icon" href="{{ \App\Support\Seo::absoluteUrl($favicon) }}">
    @endif

    @if ($isIvoIncidit)
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Cormorant+SC:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if ($gtmContainerId)
        <script>
            window.dataLayer = window.dataLayer || [];
            window.gtag = window.gtag || function(){window.dataLayer.push(arguments);};
            window.gtag('consent', 'default', {
                analytics_storage: 'denied',
                ad_storage: 'denied',
                ad_user_data: 'denied',
                ad_personalization: 'denied',
                wait_for_update: 500,
            });

            window.ivoLoadGtm = window.ivoLoadGtm || function () {
                if (window.ivoGtmLoaded) return;
                window.ivoGtmLoaded = true;

                const script = document.createElement('script');
                script.async = true;
                script.src = 'https://www.googletagmanager.com/gtm.js?id=' + encodeURIComponent(@json($gtmContainerId));
                document.head.appendChild(script);
            };

            window.ivoSetAnalyticsConsent = window.ivoSetAnalyticsConsent || function (value) {
                const granted = value === 'granted';
                window.gtag('consent', 'update', {
                    analytics_storage: granted ? 'granted' : 'denied',
                    ad_storage: 'denied',
                    ad_user_data: 'denied',
                    ad_personalization: 'denied',
                });

                if (granted) {
                    window.dataLayer.push({event: 'analytics_consent_granted'});
                    window.ivoLoadGtm();
                }
            };

            const savedAnalyticsConsent = document.cookie.match(/(?:^|; )ivo_analytics_consent=([^;]+)/)?.[1] || window.localStorage.getItem('ivo_analytics_consent');
            if (savedAnalyticsConsent) window.ivoSetAnalyticsConsent(savedAnalyticsConsent);
        </script>
    @endif
</head>
<body @class([
    'site-shell',
    'theme-' . config('maracuja.theme', 'default'),
    'theme-' . $clientTheme => filled($clientTheme),
])>
    <header class="site-header" data-nav>
        <div @class(['container', 'site-header__inner', 'site-header--ivo' => $isIvoIncidit])>
            <a class="site-brand" href="{{ route('home') }}">
                @if ($brandLogo)
                    <span class="site-brand__mark site-brand__mark--image" aria-hidden="true">
                        <x-site.image :src="$brandLogo" alt="" loading="eager" fetchpriority="high" />
                    </span>
                @else
                    <span class="site-brand__mark">M</span>
                @endif
                <span>
                    <strong>{{ $settings->site_name }}</strong>
                    @if ($settings->baseline)
                        <small>{{ $settings->baseline }}</small>
                    @endif
                </span>
            </a>

            <button class="btn btn--secondary nav-toggle" data-nav-toggle type="button">
                {{ $isIvoIncidit ? '☰' : 'Menu' }}
            </button>

            <nav class="site-nav" data-nav-menu aria-label="Navigation principale">
                @if ($isIvoIncidit)
                    <ul>
                        @if (\App\Support\Modules::enabled('arcus'))
                            <li class="site-nav__parent">
                                <a href="{{ route('arcus.index') }}">Archets</a>
                                <ul class="site-nav__submenu">
                                    <li><a href="{{ route('arcus.range', 'ars-antiqua') }}">Ars Antiqua</a></li>
                                    <li><a href="{{ route('arcus.range', 'ars-classica') }}">Ars Classica</a></li>
                                    <li><a href="{{ route('arcus.range', 'ars-nova') }}">Ars Nova</a></li>
                                </ul>
                            </li>
                        @endif
                        <li><a href="{{ route('atelier.probatio') }}">Essai</a></li>
                        <li><a href="{{ route('atelier.officina') }}">Archetier</a></li>
                        @if (\App\Support\Modules::enabled('news'))
                            <li><a href="{{ route('news.index') }}">Actualités</a></li>
                        @endif
                        @if (\App\Support\Modules::enabled('contact'))
                            <li><a href="{{ route('contact') }}">Contact</a></li>
                        @endif
                    </ul>
                @else
                    <a href="{{ route('home') }}">Accueil</a>
                    @if (config('maracuja.theme') === 'atelier')
                        <a href="{{ route('atelier.officina') }}">L’archetier</a>
                    @endif
                    @if (\App\Support\Modules::enabled('arcus'))
                        <a href="{{ route('arcus.index') }}">Archets</a>
                    @endif
                    @if (config('maracuja.theme') === 'atelier')
                        <a href="{{ route('atelier.probatio') }}">Essayer</a>
                    @endif
                    @if (\App\Support\Modules::enabled('articles'))
                        <a href="{{ route('articles.index') }}">{{ \App\Support\ContentSlots::value('articles.public_label', 'Articles') }}</a>
                    @endif
                    @if (\App\Support\Modules::enabled('news'))
                        <a href="{{ route('news.index') }}">Actualités</a>
                    @endif
                    @if (\App\Support\Modules::enabled('pages'))
                        @unless (config('maracuja.theme') === 'atelier')
                            <a href="{{ route('pages.show', 'services') }}">Services</a>
                        @endunless
                    @endif
                    @if (\App\Support\Modules::enabled('contact'))
                        <a href="{{ route('contact') }}">Contact</a>
                    @endif
                    <a href="/admin">Admin</a>
                @endif
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="site-footer container">
        <p>&copy; {{ now()->year }} {{ $settings->site_name }}</p>

        <nav class="site-footer__links">
            <a href="{{ route('atelier.legal') }}">Mentions légales</a>
            <a href="{{ route('atelier.terms') }}">CGV</a>
            <a href="{{ route('contact') }}">Contact</a>
        </nav>
        <x-site.social-links :settings="$settings" />
    </footer>

    @if ($gtmContainerId)
        <aside class="consent-banner" data-consent-banner aria-label="Choix des cookies" hidden>
            <strong>Mesure d’audience</strong>
            <p>Avec votre accord, nous utilisons une mesure d’audience pour comprendre l’usage du site.</p>
            <p><a href="{{ route('atelier.legal') }}">En savoir plus</a></p>
            <div class="consent-banner__actions">
                <button class="btn btn--primary" type="button" data-consent="granted">Accepter</button>
                <button class="btn btn--secondary" type="button" data-consent="denied">Refuser</button>
            </div>
        </aside>
        <script>
            (() => {
                const banner = document.querySelector('[data-consent-banner]');
                const key = 'ivo_analytics_consent';
                const attributionKeys = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'gbraid', 'wbraid'];
                const cookieValue = document.cookie.match(new RegExp(`(?:^|; )${key}=([^;]+)`))?.[1];
                const saved = cookieValue || window.localStorage.getItem(key);

                const rememberAttribution = () => {
                    const params = new URLSearchParams(window.location.search);
                    if (!attributionKeys.some((item) => params.has(item))) return;

                    const touch = Object.fromEntries(attributionKeys
                        .filter((item) => params.has(item))
                        .map((item) => [item, params.get(item)]));
                    touch.landing_page = window.location.pathname + window.location.search;
                    touch.referrer = document.referrer || '';
                    touch.captured_at = new Date().toISOString();
                    const options = '; Max-Age=7776000; Path=/; SameSite=Lax; Secure';
                    if (!document.cookie.match(/(?:^|; )ivo_attribution_first=/)) {
                        document.cookie = `ivo_attribution_first=${encodeURIComponent(JSON.stringify(touch))}${options}`;
                    }
                    document.cookie = `ivo_attribution_last=${encodeURIComponent(JSON.stringify(touch))}${options}`;
                };

                if (! saved) {
                    banner.hidden = false;
                } else if (saved === 'granted') {
                    rememberAttribution();
                }

                document.querySelectorAll('[data-consent]').forEach((button) => {
                    button.addEventListener('click', () => {
                        const value = button.dataset.consent;
                        document.cookie = `${key}=${value}; Max-Age=15552000; Path=/; Domain=.atelierivoincidit.fr; SameSite=Lax; Secure`;
                        window.localStorage.setItem(key, value);
                        window.ivoSetAnalyticsConsent?.(value);
                        if (value === 'granted') rememberAttribution();
                        banner.hidden = true;
                    });
                });
            })();
        </script>

        @if (session('contact_submission_success'))
            <script>
                const savedAnalyticsConsent = document.cookie.match(/(?:^|; )ivo_analytics_consent=([^;]+)/)?.[1] || window.localStorage.getItem('ivo_analytics_consent');
                if (savedAnalyticsConsent === 'granted') {
                    dataLayer.push({event: 'generate_lead'});
                }
            </script>
        @endif
    @endif

    <button class="btn btn--primary back-to-top" type="button" data-back-to-top hidden aria-label="Retour en haut">
        <span class="back-to-top__icon" aria-hidden="true">↑</span>
    </button>
</body>
</html>
