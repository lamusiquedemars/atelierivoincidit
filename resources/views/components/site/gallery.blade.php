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
            $renderItem = function ($image) use ($lightbox) {
                $src = str_starts_with($image->image_path, 'http') || str_starts_with($image->image_path, '/')
                    ? $image->image_path
                    : asset('storage/' . $image->image_path);
                $caption = $image->caption ?: $image->title;

                return compact('src', 'caption');
            };
        @endphp

        <div class="showcase__items" @if ($isCarousel) data-carousel-viewport @endif>
            @if ($isCarousel)
                <div class="carousel__track">
            @endif

            @foreach ($items as $image)
                @php(['src' => $src, 'caption' => $caption] = $renderItem($image))
                @php($lightboxCaption = collect([$caption, $image->credit ? 'Crédit : ' . $image->credit : null])->filter()->join(' - '))

                <article @class(['showcase__item', 'carousel__slide' => $isCarousel])>
                    <div class="showcase__media">
                        @if ($lightbox)
                            <a
                                href="{{ $src }}"
                                data-pswp-width="{{ $image->width ?? 1600 }}"
                                data-pswp-height="{{ $image->height ?? 1000 }}"
                                @if ($lightboxCaption) data-pswp-caption="{{ $lightboxCaption }}" @endif
                                target="_blank"
                                rel="noreferrer"
                            >
                                <x-site.image
                                    :src="$image->image_path"
                                    :alt="$image->alt"
                                    :width="$image->width"
                                    :height="$image->height"
                                />
                            </a>
                        @else
                            <x-site.image
                                :src="$image->image_path"
                                :alt="$image->alt"
                                :width="$image->width"
                                :height="$image->height"
                            />
                        @endif
                    </div>

                    @if ($caption || $image->credit)
                        <div class="showcase__content">
                            @if ($caption)
                                <h3 class="showcase__item-title">{{ $caption }}</h3>
                            @endif
                            @if ($image->credit)
                                <p class="showcase__meta">Crédit : {{ $image->credit }}</p>
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
