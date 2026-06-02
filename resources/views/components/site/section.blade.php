@props([
    'variant' => null,
    'container' => 'default',
    'title' => null,
    'intro' => null,
    'eyebrow' => null,
    'innerClass' => null,
    'headingVariant' => null,
    'align' => null,
])

@php
    $containerClass = match ($container) {
        'narrow' => 'container container--narrow',
        'readable' => 'container container--readable',
        'wide' => 'container container--wide',
        'none' => null,
        default => 'container',
    };
@endphp

<section {{ $attributes->class(['section', 'section--' . $variant => $variant]) }}>
    <div @class([$containerClass => $containerClass, $innerClass => $innerClass])>
        @if ($title || $intro || $eyebrow)
            <div @class(['section__header', 'section__header--center' => $align === 'center'])>
                @if ($eyebrow)
                    <p class="eyebrow">{{ $eyebrow }}</p>
                @endif
                @if ($title)
                    <x-site.heading level="2" :variant="$headingVariant" :align="$align">
                        {{ $title }}
                    </x-site.heading>
                @endif
                @if ($intro)
                    <p class="section__intro">{{ $intro }}</p>
                @endif
            </div>
        @endif

        {{ $slot }}
    </div>
</section>
