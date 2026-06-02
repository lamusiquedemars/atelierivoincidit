@props([
    'title',
    'icon' => null,
    'href' => null,
    'label' => 'Lire',
])

<article {{ $attributes->class(['feature-card']) }}>
    @if ($icon)
        <div class="feature-card__icon">{{ $icon }}</div>
    @endif
    <h3 class="feature-card__title">{{ $title }}</h3>
    <p class="feature-card__text">{{ $slot }}</p>
    @if ($href)
        <a class="feature-card__cta card__link" href="{{ $href }}">{{ $label }}</a>
    @endif
</article>
