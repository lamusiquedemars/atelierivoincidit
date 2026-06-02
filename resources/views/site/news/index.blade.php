@extends('layouts.site', [
    'seoTitle' => 'Actualités - ' . $settings->site_name,
    'seoDescription' => 'Dernières actualités publiées.',
])

@section('content')
    <x-site.hero title="Actualités" subtitle="Les contenus récurrents publiés depuis l’admin." />

    <x-site.breadcrumb :items="[
        ['label' => 'Actualités'],
    ]" />

    <x-site.section>
        <x-site.grid columns="3" class="news-list">
            @foreach ($posts as $post)
                <x-site.card :title="$post->title" :url="$post->hasDetailPage() ? route('news.show', $post->slug) : null">
                    @if ($post->is_pinned)
                        <x-site.badge>Épinglé</x-site.badge>
                    @endif

                    {{ $post->excerpt }}
                </x-site.card>
            @endforeach
        </x-site.grid>
        {{ $posts->links() }}
    </x-site.section>
@endsection
