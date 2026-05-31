@props([
    'columns' => 3,
])

<div {{ $attributes->class(['grid', 'grid--' . $columns]) }}>
    {{ $slot }}
</div>
