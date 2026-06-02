@props([
    'author' => null,
    'meta' => null,
])

<figure {{ $attributes->class(['quote']) }}>
    <blockquote class="quote__text">{{ $slot }}</blockquote>
    @if ($author || $meta)
        <figcaption class="quote__meta">
            {{ $author }}
            @if ($author && $meta)
                <span aria-hidden="true"> - </span>
            @endif
            {{ $meta }}
        </figcaption>
    @endif
</figure>
