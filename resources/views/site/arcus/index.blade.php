@extends('layouts.site', [
    'seoTitle' => 'Archets',
    'seoDescription' => 'Archets artisanaux faits main par Ivo Correia de Melo : Ars Antiqua, Ars Classica et Ars Nova.',
])

@section('content')
    <x-site.hero
        class="hero--arcus-heads"
        eyebrow=""
        title="Archets"
        subtitle="Trois séries, trois intentions de jeu."
        variant="arcus"
        image="/assets/images/arcus-tetes.jpeg"
    />

    <x-site.section heading-variant="accent">
        <div class="prose container--narrow">
            <p>
                Mes archets sont conçus pour servir le musicien selon son niveau, son style et sa sensibilité.
                Je propose trois gammes distinctes, basées sur des critères mesurables - densité,
                élasticité, cambrure et équilibre - tout en conservant la même exigence de construction.
            </p>
            <p>
                Les noms des gammes <strong>Ars Antiqua</strong>, <strong>Ars Classica</strong> et
                <strong>Ars Nova</strong> reflètent cette approche : chaque gamme porte une intention
                de jeu différente.
            </p>
        </div>
    </x-site.section>

    <x-site.section title="Trois séries, trois manières de jouer" heading-variant="decorated">
        <x-site.grid columns="3">
            @foreach ($series as $item)
                <x-site.card :title="$item['title']" :url="$item['href']" :image="$item['image']" variant="featured">
                    {{ $item['text'] }}
                </x-site.card>
            @endforeach
        </x-site.grid>
    </x-site.section>

    <x-site.section variant="muted" title="Comment choisir ?" heading-variant="underline">
        <div class="prose container--narrow">
            <ul>
                <li>Si vous cherchez un archet baroque ou historiquement inspiré, commencez par <strong>Ars Antiqua</strong>.</li>
                <li>Si vous cherchez un archet stable, équilibré, pensé pour une pratique moderne régulière, allez vers <strong>Ars Classica</strong>.</li>
                <li>Si vous cherchez une proposition plus personnelle, moins standard, regardez du côté d’<strong>Ars Nova</strong>.</li>
            </ul>
            <p>Si vous hésitez, l’échange et l’essai restent souvent plus justes qu’un choix fait uniquement sur fiche.</p>
            <p><a class="btn btn--primary" href="{{ route('contact') }}">Me demander conseil</a></p>
        </div>
    </x-site.section>
@endsection
