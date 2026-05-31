@props([
    'src',
    'alt' => '',
    'caption' => null,
    'credit' => null,
    'width' => null,
    'height' => null,
])

<figure {{ $attributes->class(['media-figure']) }}>
    <x-site.image
        :src="$src"
        :alt="$alt"
        :width="$width"
        :height="$height"
    />

    @if ($caption || $credit)
        <figcaption class="media-figure__caption">
            @if ($caption)
                <span>{{ $caption }}</span>
            @endif
            @if ($credit)
                <small>Crédit : {{ $credit }}</small>
            @endif
        </figcaption>
    @endif
</figure>
