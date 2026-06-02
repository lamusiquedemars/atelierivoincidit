@props([
    'href',
    'label' => 'Retour',
])

<a {{ $attributes->class(['back-link'])->merge(['href' => $href]) }}>
    <span aria-hidden="true">←</span>
    <span>{{ $label }}</span>
</a>
