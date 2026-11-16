@extends('layouts.site', [
    'seoTitle' => $label . ' - ' . $settings->site_name,
    'seoDescription' => $subtitle,
    'seoRobots' => $posts->isEmpty() ? 'noindex, follow' : null,
])

@section('content')
    <x-site.hero
        eyebrow="Article"
        title="Notes d’atelier"
        subtitle="J’écris sur les bois que je travaille, sur l’histoire de l’archet et sur ce que la fabrication et les essais m’apprennent."
        variant="page"
    />

    <x-site.breadcrumb :items="[['label' => $label]]" />

    <x-site.section>
        @if ($posts->isEmpty())
            <div class="prose">
                <p>Aucun article publié pour le moment.</p>
            </div>
        @else
            <x-site.grid columns="3">
                @foreach ($posts as $post)
                    <x-site.card
                        :title="$post->title"
                        :url="route('articles.show', $post->slug)"
                        :image="$post->imageUrl() ?: '/assets/images/merle.png'"
                        variant="featured"
                    >
                        {{ $post->publicExcerpt() }}
                    </x-site.card>
                @endforeach
            </x-site.grid>
            {{ $posts->links('site.articles.pagination') }}
        @endif
    </x-site.section>
@endsection
