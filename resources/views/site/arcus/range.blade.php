@extends('layouts.site', [
    'seoTitle' => $content['title'],
    'seoDescription' => $content['description'],
])

@section('content')
    <x-site.hero
        eyebrow="Arcus"
        :title="$content['title']"
        :subtitle="$content['heroSubtitle']"
        variant="arcus"
        :image="$content['image']"
    />

    <x-site.breadcrumb :items="[
        ['label' => 'Archets', 'url' => route('arcus.index')],
        ['label' => $content['title']],
    ]" />

    <x-site.section :title="$content['introTitle']" heading-variant="accent">
        <div class="prose container--narrow">
            <p>{{ $content['introText'] }}</p>
            <h3>{{ $content['intentionTitle'] }}</h3>
            <p>{{ $content['intentionText'] }}</p>
            <h3>{{ $content['forWhoTitle'] }}</h3>
            <ul>
                @foreach ($content['forWhoItems'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
            <h3>{{ $content['notTitle'] }}</h3>
            <p>{{ $content['notText'] }}</p>
        </div>
    </x-site.section>

    <x-site.section :title="$content['availableTitle']" heading-variant="decorated">
        @if ($bows->isNotEmpty())
            <x-site.grid columns="3">
                @foreach ($bows as $bow)
                    @include('site.arcus.partials.bow-card', ['bow' => $bow])
                @endforeach
            </x-site.grid>
        @else
            <div class="prose container--narrow">
                <p>{{ $content['emptyText'] }}</p>
                <p><a class="btn btn--primary" href="{{ route('contact') }}">Me parler de votre recherche</a></p>
            </div>
        @endif
    </x-site.section>

    <x-site.section variant="muted" :title="$content['finalTitle']" heading-variant="underline">
        <div class="prose container--narrow">
            <p>{{ $content['finalText'] }}</p>
            <p><a class="btn btn--primary" href="{{ route('contact') }}">Me demander conseil</a></p>
        </div>
    </x-site.section>
@endsection
