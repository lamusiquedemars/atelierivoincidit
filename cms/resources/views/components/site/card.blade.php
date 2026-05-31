@props([
    'title',
    'kicker' => null,
    'url' => null,
    'variant' => null,
    'image' => null,
])

<article {{ $attributes->class(['card', 'card--' . $variant => $variant]) }}>
    @if ($image)
        <div class="card__media">
            <img src="{{ $image }}" alt="">
        </div>
    @endif

    <div @class(['card__content' => $image || in_array($variant, ['media', 'horizontal'], true)])>
        @if (! empty($kicker))
            <p class="card__kicker">{{ $kicker }}</p>
        @endif
        <h3>{{ $title }}</h3>
        <p class="card__body">{{ $slot }}</p>
        @if (! empty($url))
            <a class="card__link" href="{{ $url }}">Lire</a>
        @endif
    </div>
</article>
