@props([
    'href' => null,
    'variant' => 'primary',
    'size' => null,
    'type' => 'button',
])

@if ($href)
    <a {{ $attributes->class(['btn', 'btn--' . $variant, 'btn--' . $size => $size])->merge(['href' => $href]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->class(['btn', 'btn--' . $variant, 'btn--' . $size => $size])->merge(['type' => $type]) }}>
        {{ $slot }}
    </button>
@endif
