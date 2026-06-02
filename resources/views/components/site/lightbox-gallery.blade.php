@props([
    'images',
    'layout' => 'grid',
])

<x-site.gallery :images="$images" :layout="$layout" lightbox {{ $attributes }} />
