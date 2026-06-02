@props([
    'items',
    'kicker' => null,
    'title' => null,
    'intro' => null,
    'variant' => 'editorial',
    'itemsPerView' => 1,
    'showControls' => true,
    'showDots' => true,
])

@php
    $itemsPerView = max(1, min(4, (int) $itemsPerView));
    $quotes = collect($items)
        ->filter(fn ($item) => is_array($item) && filled(trim((string) ($item['quote'] ?? ''))))
        ->values();
    $hasMultipleSlides = $quotes->count() > 1;
@endphp

@if ($quotes->isNotEmpty())
    <div
        {{ $attributes
            ->class([
                'quote-carousel',
                'quote-carousel--' . $variant,
                'carousel',
                'carousel--items-' . $itemsPerView,
            ])
            ->merge([
                'data-carousel' => true,
                'data-carousel-loop' => 'false',
            ]) }}
    >
        @if ($kicker || $title || $intro)
            <header class="quote-carousel__header">
                @if ($kicker)
                    <p class="quote-carousel__kicker">{{ $kicker }}</p>
                @endif
                @if ($title)
                    <h2 class="quote-carousel__title">{{ $title }}</h2>
                @endif
                @if ($intro)
                    <p class="quote-carousel__intro">{{ $intro }}</p>
                @endif
            </header>
        @endif

        <div class="quote-carousel__viewport carousel__viewport" data-carousel-viewport>
            <div class="quote-carousel__track carousel__track">
                @foreach ($quotes as $quote)
                    <blockquote class="quote-carousel__slide carousel__slide">
                        <p class="quote-carousel__quote">« {{ $quote['quote'] }} »</p>

                        @if (filled($quote['author'] ?? null) || filled($quote['meta'] ?? null))
                            <footer class="quote-carousel__footer">
                                @if (filled($quote['author'] ?? null))
                                    <span class="quote-carousel__author">{{ $quote['author'] }}</span>
                                @endif
                                @if (filled($quote['meta'] ?? null))
                                    <span class="quote-carousel__meta">{{ $quote['meta'] }}</span>
                                @endif
                            </footer>
                        @endif
                    </blockquote>
                @endforeach
            </div>
        </div>

        @if ($hasMultipleSlides && ($showControls || $showDots))
            <div class="quote-carousel__controls carousel__controls">
                @if ($showControls)
                    <button class="btn btn--secondary btn--small" data-carousel-prev type="button">Précédent</button>
                @endif
                @if ($showDots)
                    <div class="carousel__dots" data-carousel-dots></div>
                @endif
                @if ($showControls)
                    <button class="btn btn--secondary btn--small" data-carousel-next type="button">Suivant</button>
                @endif
            </div>
        @endif
    </div>
@endif
