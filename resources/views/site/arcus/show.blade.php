@php
    $bowTitle = trim(($bow['range_name'] ?? '').' "'.(! empty($bow['atelier_name']) ? $bow['atelier_name'] : 'n° '.$bow['id']).'"');
    $bowSubtitle = implode(' · ', array_filter([
        'Archet '.($bow['style_name'] ?? ''),
        $bow['instrument_name'] ?? null,
        $bow['size_name'] ?? null,
    ]));
@endphp

@extends('layouts.site', [
    'seoTitle' => $bowTitle,
    'seoDescription' => $bowSubtitle,
])

@section('content')
    <x-site.hero
        class="hero--arcus-heads"
        eyebrow="Archets"
        :title="$bowTitle"
        :subtitle="$bowSubtitle"
        variant="arcus"
        image="/assets/images/arcus-tetes.jpeg"
    />

    <x-site.breadcrumb :items="[
        ['label' => 'Archets', 'url' => route('arcus.index')],
        ['label' => $bow['range_name'] ?? 'Gamme', 'url' => route('arcus.range', $bow['range_slug'])],
        ['label' => $bow['code']],
    ]" />

    <x-site.section>
        <div class="container container--narrow stack">
            @if ($priceData !== null || $statusLabel !== '')
                <p class="price">
                    @if ($priceData !== null)
                        <span>{{ \App\Modules\Arcus\Support\ArcusCatalog::formatPrice($priceData['current']) }}</span>
                        @if ($priceData['old'] !== null)
                            <span class="price-old">{{ \App\Modules\Arcus\Support\ArcusCatalog::formatPrice($priceData['old']) }}</span>
                        @endif
                    @endif
                    @if ($statusLabel !== '')
                        <x-site.badge>{{ $statusLabel }}</x-site.badge>
                    @endif
                </p>
            @endif

            @if (! empty($bow['short_trait']))
                <p>{{ $bow['short_trait'] }}</p>
            @endif
        </div>
    </x-site.section>

    @if ($photos->isNotEmpty())
        <x-site.section>
            <x-site.gallery :images="$photos" layout="carousel" lightbox />
        </x-site.section>
    @endif

    <x-site.section title="Caractéristiques de l’archet" heading-variant="decorated">
        <x-site.grid columns="3">
            <article class="card">
                <h3>Caractère de jeu</h3>
                <ul>
                    <li><strong>Flexibilité :</strong> {{ $bow['flexibility_name'] ?? '' }}</li>
                    <li><strong>Réactivité :</strong> {{ $bow['responsiveness_name'] ?? '' }}</li>
                    <li><strong>Maniabilité :</strong> {{ $bow['handling_name'] ?? '' }}</li>
                    <li><strong>Pression naturelle :</strong> {{ $bow['natural_pressure_name'] ?? '' }}</li>
                    <li><strong>Timbre dominant :</strong> {{ $bow['tone_name'] ?? '' }}</li>
                    <li><strong>Projection :</strong> {{ $bow['projection_name'] ?? '' }}</li>
                    <li><strong>Sustain :</strong> {{ $bow['sustain_name'] ?? '' }}</li>
                    <li><strong>Articulation :</strong> {{ $bow['articulation_name'] ?? '' }}</li>
                </ul>
            </article>

            <article class="card">
                <h3>Fabrication et matériaux</h3>
                <ul>
                    <li><strong>Instrument :</strong> {{ $bow['instrument_name'] ?? '' }}</li>
                    <li><strong>Taille :</strong> {{ $bow['size_name'] ?? '' }}</li>
                    <li><strong>Style :</strong> {{ $bow['style_name'] ?? '' }}</li>
                    <li><strong>Forme :</strong> {{ $bow['shape_name'] ?? '' }}</li>
                    <li><strong>Bois :</strong> {{ $bow['wood_name'] ?? '' }} @if (! empty($bow['origin_name'])) - {{ $bow['origin_name'] }} @endif</li>
                    <li><strong>Couleur :</strong> {{ $bow['color_name'] ?? '' }}</li>
                    <li><strong>Hausse :</strong> {{ $bow['frog_material_name'] ?? '' }}</li>
                    <li><strong>Coulisse :</strong> {{ $bow['slide_material_name'] ?? '' }}</li>
                    <li><strong>Bouton :</strong> {{ $bow['button_material_name'] ?? '' }}</li>
                    <li><strong>Pointe :</strong> {{ $bow['tip_material_name'] ?? '' }}</li>
                    <li><strong>Garniture :</strong> {{ $bow['garnish_name'] ?? '' }}</li>
                </ul>
            </article>

            <article class="card">
                <h3>Mesures d’atelier</h3>
                <table>
                    <tr><th>Poids baguette</th><td>{{ $bow['stick_weight'] ?? '' }} g</td></tr>
                    <tr><th>Poids total</th><td>{{ $bow['total_weight'] ?? '' }} g</td></tr>
                    <tr><th>Longueur baguette</th><td>{{ $bow['stick_length'] ?? '' }} mm</td></tr>
                    <tr><th>Longueur totale</th><td>{{ $bow['total_length'] ?? '' }} mm</td></tr>
                    <tr><th>Équilibre</th><td>{{ $bow['balance_point'] ?? '' }} mm</td></tr>
                    <tr><th>Densité</th><td>{{ $bow['density'] ?? '' }} g/cm³</td></tr>
                    <tr><th>Vitesse du son</th><td>{{ $bow['speed'] ?? '' }} m/s</td></tr>
                    <tr><th>Élasticité</th><td>{{ $bow['elasticity'] ?? '' }} GPa</td></tr>
                    <tr><th>Fréquence</th><td>{{ $bow['frequency'] ?? '' }} Hz</td></tr>
                    <tr><th>Amortissement</th><td>{{ $bow['damping'] ?? '' }}</td></tr>
                </table>
            </article>
        </x-site.grid>
    </x-site.section>

    @if (! empty($bow['notes']))
        <x-site.section title="Notes de l’archetier" heading-variant="underline">
            <div class="prose container--narrow">
                {!! nl2br(e($bow['notes'])) !!}
            </div>
        </x-site.section>
    @endif

    <x-site.section variant="muted">
        <x-site.cta
            class="cta--arcus-trial"
            title="Essayer cet archet"
            text="Les mesures orientent, mais le choix se confirme surtout avec l’instrument, dans le geste et dans l’écoute."
            :href="route('contact')"
            label="Demander à essayer cet archet"
            inline
        />
    </x-site.section>
@endsection
