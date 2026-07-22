@props([
    'images',
    'layout' => 'grid',
    'lightbox' => false,
    'itemsPerView' => null,
])

@php
    $allowedLayouts = ['grid', 'featured', 'carousel'];
    $layout = in_array($layout, $allowedLayouts, true) ? $layout : 'grid';
    $isCarousel = $layout === 'carousel';
    $itemsPerView = $itemsPerView === null ? null : max(1, min(4, (int) $itemsPerView));
    $items = collect($images)->values();
@endphp

@if ($items->isNotEmpty())
    <div
        {{ $attributes
            ->class([
                'media-gallery',
                'showcase',
                'showcase--' . $layout,
                'carousel--items-' . $itemsPerView => $isCarousel && $itemsPerView,
            ])
            ->merge($lightbox ? ['data-lightbox' => true] : [])
            ->merge($isCarousel ? ['data-carousel' => true] : []) }}
    >
        @php
            $renderItem = function ($image) {
                $media = data_get($image, 'media');
                $src = data_get($image, 'resolved_image_url') ?: data_get($image, 'image_path');

                if ($src
                    && ! str_starts_with($src, 'http://')
                    && ! str_starts_with($src, 'https://')
                    && ! str_starts_with($src, '/')) {
                    $src = asset('storage/'.$src);
                }

                $caption = data_get($image, 'caption') ?: data_get($media, 'caption') ?: data_get($image, 'title');
                $credit = data_get($image, 'credit') ?: data_get($media, 'credit');
                $alt = data_get($image, 'alt') ?: data_get($image, 'alt_text') ?: data_get($media, 'alt_text') ?: '';
                $width = data_get($image, 'width');
                $height = data_get($image, 'height');

                return compact('src', 'caption', 'credit', 'alt', 'width', 'height');
            };
        @endphp

        <div class="showcase__items" @if ($isCarousel) data-carousel-viewport @endif>
            @if ($isCarousel)
                <div class="carousel__track">
            @endif

            @foreach ($items as $image)
                @php(['src' => $src, 'caption' => $caption, 'credit' => $credit, 'alt' => $alt, 'width' => $width, 'height' => $height] = $renderItem($image))
                @php($lightboxCaption = collect([$caption, $credit ? 'Crédit : ' . $credit : null])->filter()->join(' - '))

                <article @class(['showcase__item', 'carousel__slide' => $isCarousel])>
                    <div class="showcase__media">
                        @if ($lightbox)
                            <a
                                href="{{ $src }}"
                                data-pswp-width="{{ $width ?? 1600 }}"
                                data-pswp-height="{{ $height ?? 1000 }}"
                                @if ($lightboxCaption) data-pswp-caption="{{ $lightboxCaption }}" @endif
                                target="_blank"
                                rel="noreferrer"
                            >
                                <x-site.image
                                    :src="$src"
                                    :alt="$alt"
                                    :width="$width"
                                    :height="$height"
                                />
                            </a>
                        @else
                            <x-site.image
                                :src="$src"
                                :alt="$alt"
                                :width="$width"
                                :height="$height"
                            />
                        @endif
                    </div>

                    @if ($caption || $credit)
                        <div class="showcase__content">
                            @if ($caption)
                                <h3 class="showcase__item-title">{{ $caption }}</h3>
                            @endif
                            @if ($credit)
                                <p class="showcase__meta">Crédit : {{ $credit }}</p>
                            @endif
                        </div>
                    @endif
                </article>
            @endforeach

            @if ($isCarousel)
            </div>
            @endif
        </div>

        @if ($isCarousel && $items->count() > 1)
            <div class="carousel__controls">
                <button class="btn btn--secondary btn--small" data-carousel-prev type="button">Précédent</button>
                <button class="btn btn--secondary btn--small" data-carousel-next type="button">Suivant</button>
            </div>
        @endif
    </div>
@endif
