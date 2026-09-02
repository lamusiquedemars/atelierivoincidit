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

    if (($width === null || $height === null) && str_starts_with($resolvedSrc, '/')) {
        $publicPath = public_path(ltrim((string) parse_url($resolvedSrc, PHP_URL_PATH), '/'));
        $dimensions = is_file($publicPath) ? @getimagesize($publicPath) : false;

        if ($dimensions !== false) {
            $width ??= $dimensions[0];
            $height ??= $dimensions[1];
        }
    }
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
