@props([
    'level' => 2,
    'variant' => null,
    'align' => null,
])

@php
    $tag = in_array((int) $level, [1, 2, 3, 4], true) ? 'h' . (int) $level : 'h2';
@endphp

<{{ $tag }} {{ $attributes->class([
    'heading',
    'heading--' . $variant => $variant,
    'heading--center' => $align === 'center',
]) }}>
    {{ $slot }}
</{{ $tag }}>
