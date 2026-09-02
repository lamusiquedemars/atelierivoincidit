@props([
    'title',
    'kicker' => null,
    'url' => null,
    'variant' => null,
    'image' => null,
    'imageAlt' => null,
])

<article {{ $attributes->class(['card', 'card--' . $variant => $variant]) }}>
    @if ($image)
        <div class="card__media">
            <x-site.image :src="$image" :alt="$imageAlt ?? $title" />
        </div>
    @endif

    <div @class(['card__content' => $image || in_array($variant, ['media', 'horizontal'], true)])>
        @if (! empty($kicker))
            <p class="card__kicker">{{ $kicker }}</p>
        @endif
        <h3>{{ $title }}</h3>
        <p class="card__body">{{ $slot }}</p>
        @if (! empty($url))
            <a class="card__link" href="{{ $url }}">Découvrir {{ $title }}</a>
        @endif
    </div>
</article>
