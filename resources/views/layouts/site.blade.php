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
        ]);

        $clientTheme = config('maracuja.client_theme');
        $isIvoIncidit = $clientTheme === 'ivo-incidit';
        $brandLogo = $settings->logo_path ?: ($isIvoIncidit ? '/assets/images/blason-ivo-incidit2.png' : null);
        $ivoSocialLinks = $settings->social_links ?: ['Instagram : @ivo_incidit' => 'https://instagram.com/ivo_incidit'];
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

    @if ($settings->favicon_path)
        <link rel="icon" href="{{ \App\Support\Seo::absoluteUrl($settings->favicon_path) }}">
    @endif

    @if ($isIvoIncidit)
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Cormorant+SC:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                        <img src="{{ $brandLogo }}" alt="">
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
            @foreach ($ivoSocialLinks as $label => $url)
                <span aria-hidden="true">•</span>
                <a href="{{ $url }}" target="_blank" rel="noopener">{{ $label }}</a>
            @endforeach
        </nav>
    </footer>

    <button class="btn btn--primary back-to-top" type="button" data-back-to-top hidden aria-label="Retour en haut">
        <span class="back-to-top__icon" aria-hidden="true">↑</span>
    </button>
</body>
</html>
