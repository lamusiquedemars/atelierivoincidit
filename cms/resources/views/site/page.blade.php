@extends('layouts.site', [
    'seoTitle' => $page->seo_title,
    'seoDescription' => $page->seo_description,
    'seoImage' => $page->hero_image_path,
])

@section('content')
    <x-site.hero
        :title="$page->hero_title ?? $page->title"
        :subtitle="$page->hero_subtitle ?? $page->excerpt"
    />

    <x-site.breadcrumb :items="[
        ['label' => $page->title],
    ]" />

    <x-site.section container="narrow" inner-class="prose">
        <h2>{{ $page->title }}</h2>
        @if ($page->excerpt)
            <p class="lead">{{ $page->excerpt }}</p>
        @endif
        @foreach (($page->body_blocks ?? []) as $title => $content)
            <article class="content-block">
                <h3>{{ str_replace('_', ' ', ucfirst($title)) }}</h3>
                <p>{{ $content }}</p>
            </article>
        @endforeach

        <x-site.back-link :href="route('home')" label="Retour à l’accueil" />
    </x-site.section>
@endsection
