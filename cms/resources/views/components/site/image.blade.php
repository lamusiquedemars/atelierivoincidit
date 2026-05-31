@props([
    'src',
    'alt' => '',
    'width' => null,
    'height' => null,
    'loading' => 'lazy',
    'decoding' => 'async',
])

@php
    $resolvedSrc = str_starts_with($src, 'http') || str_starts_with($src, '/') ? $src : asset('storage/' . $src);
@endphp

<img
    {{ $attributes->merge([
        'src' => $resolvedSrc,
        'alt' => $alt,
        'width' => $width,
        'height' => $height,
        'loading' => $loading,
        'decoding' => $decoding,
    ]) }}
>
