@props([
    'title' => null,
    'text' => null,
    'href' => null,
    'label' => null,
    'variant' => null,
    'inline' => false,
])

<aside {{ $attributes->class(['cta', 'cta--' . $variant => $variant, 'cta--inline' => $inline]) }}>
    <div class="stack stack--sm">
        @if ($title)
            <h2>{{ $title }}</h2>
        @endif
        @if ($text)
            <p class="text-muted">{{ $text }}</p>
        @endif
        {{ $slot }}
    </div>

    @if ($href && $label)
        <x-site.button :href="$href">{{ $label }}</x-site.button>
    @endif
</aside>
