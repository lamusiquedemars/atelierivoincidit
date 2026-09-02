@extends('layouts.site', [
    'seoTitle' => $post->seo_title ?? $post->title,
    'seoDescription' => $post->seo_description ?? $post->publicExcerpt(),
    'seoImage' => $post->imageUrl(),
    'seoType' => 'article',
])

@push('structured-data')
    <script type="application/ld+json">{!! \App\Support\StructuredData::json(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        '@id' => url()->current().'#article',
        'mainEntityOfPage' => url()->current(),
        'headline' => $post->title,
        'description' => $post->seo_description ?? $post->publicExcerpt(),
        'image' => $post->imageUrl() ? \App\Support\Seo::absoluteUrl($post->imageUrl()) : null,
        'datePublished' => $post->published_at?->toAtomString(),
        'dateModified' => $post->updated_at?->toAtomString(),
        'author' => ['@id' => rtrim(config('app.url'), '/').'/#ivo-correia-de-melo'],
        'publisher' => ['@id' => rtrim(config('app.url'), '/').'/#organization'],
        'inLanguage' => 'fr-FR',
    ], fn (mixed $value): bool => $value !== null)) !!}</script>
@endpush

@section('content')
    <x-site.hero
        :eyebrow="$label"
        :title="$post->title"
        :subtitle="$post->published_at?->translatedFormat('d F Y')"
        variant="page"
    />

    <x-site.breadcrumb :items="[
        ['label' => $label, 'url' => route('articles.index')],
        ['label' => $post->title],
    ]" />

    <x-site.section container="readable">
        <article class="article-content prose">
            @if ($post->imageUrl())
                <x-site.figure
                    :src="$post->imageUrl()"
                    :alt="$post->title"
                />
            @endif

            {{ \App\Support\ArticleBlocks::render($post->body_blocks) }}

            <x-site.back-link :href="route('articles.index')" :label="strtolower($label) === 'articles' ? 'Retour aux articles' : 'Retour à ' . strtolower($label)" />
        </article>
    </x-site.section>
@endsection
