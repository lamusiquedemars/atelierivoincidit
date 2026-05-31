<article class="card arcus-card">
    @if ($bow['image'] !== '')
        <figure class="card__media arcus-card__media">
            <x-site.image class="arcus-card__image" :src="$bow['image']" :alt="$bow['alt']" />
        </figure>
    @endif

    <div class="stack stack--sm">
        <h3>{{ $bow['title'] }}</h3>

        @if ($bow['meta'] !== '')
            <p class="text-muted">{{ $bow['meta'] }}</p>
        @endif

        @if ($bow['text'] !== '')
            <p>{{ $bow['text'] }}</p>
        @endif

        @if ($bow['priceData'] !== null)
            <p class="price">
                <span>{{ \App\Modules\Arcus\Support\ArcusCatalog::formatPrice($bow['priceData']['current']) }}</span>
                @if ($bow['priceData']['old'] !== null)
                    <span class="price-old">{{ \App\Modules\Arcus\Support\ArcusCatalog::formatPrice($bow['priceData']['old']) }}</span>
                @endif
            </p>
        @endif

        @if ($bow['statusLabel'] !== '')
            <p><x-site.badge>{{ $bow['statusLabel'] }}</x-site.badge></p>
        @endif

        <p class="card-footer">
            <a class="btn btn--secondary" href="{{ $bow['href'] }}">{{ $bow['ctaLabel'] }}</a>
        </p>
    </div>
</article>
