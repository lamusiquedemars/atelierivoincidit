@props([
    'notice',
])

@php
    $tone = in_array($notice->tone, ['info', 'success', 'warning'], true) ? $notice->tone : 'info';
@endphp

<aside {{ $attributes->class(['notice', 'notice--' . $tone]) }}>
    <div class="notice__content">
        @if ($notice->title)
            <strong class="notice__title">{{ $notice->title }}</strong>
        @endif

        <p class="notice__message">{{ $notice->message }}</p>
    </div>

    @if ($notice->link_label && $notice->link_url)
        <a class="notice__link" href="{{ $notice->link_url }}">{{ $notice->link_label }}</a>
    @endif
</aside>
