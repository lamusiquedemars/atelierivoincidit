@props([
    'items' => [],
])

@if (count($items) > 0)
    <nav {{ $attributes->class(['breadcrumb container'])->merge(['aria-label' => 'Fil d’Ariane']) }}>
        <ol class="breadcrumb__list">
            <li class="breadcrumb__item">
                <a href="{{ route('home') }}">Accueil</a>
            </li>

            @foreach ($items as $item)
                @php
                    $label = is_array($item) ? ($item['label'] ?? '') : $item;
                    $url = is_array($item) ? ($item['url'] ?? null) : null;
                    $isCurrent = $loop->last || blank($url);
                @endphp

                <li class="breadcrumb__item" @if ($isCurrent) aria-current="page" @endif>
                    @if ($url && ! $loop->last)
                        <a href="{{ $url }}">{{ $label }}</a>
                    @else
                        <span>{{ $label }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
