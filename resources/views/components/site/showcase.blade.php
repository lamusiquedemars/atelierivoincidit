@props([
    'title' => null,
    'intro' => null,
    'variant' => null,
])

<section {{ $attributes->class(['showcase', 'showcase--' . $variant => $variant]) }}>
    @if ($title || $intro)
        <header class="showcase__header stack stack--sm">
            @if ($title)
                <h2>{{ $title }}</h2>
            @endif
            @if ($intro)
                <p class="showcase__intro text-muted">{{ $intro }}</p>
            @endif
        </header>
    @endif

    {{ $slot }}
</section>
