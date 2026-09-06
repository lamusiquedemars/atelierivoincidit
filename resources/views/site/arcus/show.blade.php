@php
    $bowTitle = trim(($bow['range_name'] ?? '').' "'.(! empty($bow['atelier_name']) ? $bow['atelier_name'] : 'n° '.$bow['id']).'"');
    $bowSubtitle = implode(' · ', array_filter([
        'Archet '.($bow['style_name'] ?? ''),
        $bow['instrument_name'] ?? null,
        $bow['size_name'] ?? null,
    ]));
    $bowStyle = mb_strtolower((string) ($bow['style_name'] ?? ''));
    $bowInstrument = mb_strtolower((string) ($bow['instrument_name'] ?? ''));
    $bowSearchLabel = $bowStyle === 'baroque'
        ? trim('Archet baroque de '.$bowInstrument)
        : trim('Archet de '.$bowInstrument);
    $bowSeoTitle = trim(implode(' – ', array_filter([
        $bowSearchLabel,
        $bowTitle,
    ])).' | Ivo Incidit');
    $bowSeoDescription = rtrim(implode('. ', array_filter([
        $bowSubtitle,
        $bow['short_trait'] ?? null,
        ! empty($bow['wood_name']) ? 'Baguette en '.$bow['wood_name'] : null,
    ])), '. ').'.';
    $measurements = [
        ['label' => 'Poids baguette', 'value' => $bow['stick_weight_display'] ?? '', 'unit' => 'g'],
        ['label' => 'Poids total', 'value' => $bow['total_weight_display'] ?? '', 'unit' => 'g'],
        ['label' => 'Longueur baguette', 'value' => $bow['stick_length_display'] ?? '', 'unit' => 'mm'],
        ['label' => 'Longueur totale', 'value' => $bow['total_length_display'] ?? '', 'unit' => 'mm'],
        ['label' => 'Équilibre', 'value' => $bow['balance_point_display'] ?? '', 'unit' => 'mm'],
        ['label' => 'Densité', 'value' => $bow['density_display'] ?? '', 'unit' => 'kg/m³'],
        ['label' => 'Vitesse du son', 'value' => $bow['speed_display'] ?? '', 'unit' => 'm/s'],
        ['label' => 'Élasticité', 'value' => $bow['elasticity_display'] ?? '', 'unit' => 'GPa'],
        ['label' => 'Fréquence', 'value' => $bow['frequency_display'] ?? '', 'unit' => 'Hz'],
        ['label' => 'Amortissement', 'value' => $bow['damping_display'] ?? '', 'unit' => null],
    ];

    $productData = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        '@id' => url()->current().'#product',
        'name' => $bowTitle,
        'description' => $bowSeoDescription,
        'sku' => strtoupper($bow['code']),
        'category' => $bowSubtitle,
        'material' => $bow['wood_name'] ?? null,
        'image' => $photos->pluck('resolved_image_url')->map(fn (string $url) => \App\Support\Seo::absoluteUrl($url))->values()->all() ?: null,
        'brand' => ['@id' => rtrim(config('app.url'), '/').'/#organization'],
        'manufacturer' => ['@id' => rtrim(config('app.url'), '/').'/#organization'],
        'offers' => $priceData === null ? null : [
            '@type' => 'Offer',
            'url' => url()->current(),
            'priceCurrency' => 'EUR',
            'price' => number_format($priceData['current'] / 100, 2, '.', ''),
            'availability' => match ($bow['status'] ?? null) {
                'available' => 'https://schema.org/InStock',
                'reserved' => 'https://schema.org/LimitedAvailability',
                default => 'https://schema.org/OutOfStock',
            },
            'seller' => ['@id' => rtrim(config('app.url'), '/').'/#organization'],
        ],
    ], fn (mixed $value): bool => $value !== null);
@endphp

@extends('layouts.site', [
    'seoTitle' => $bowSeoTitle,
    'seoDescription' => $bowSeoDescription,
])

@push('structured-data')
    <script type="application/ld+json">{!! \App\Support\StructuredData::json($productData) !!}</script>
@endpush

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
            <x-site.gallery :images="$photos" layout="carousel" lightbox :show-meta="false" />
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
                    @foreach ($measurements as $measurement)
                        @if ($measurement['value'] !== '')
                            <tr>
                                <th>{{ $measurement['label'] }}</th>
                                <td>{{ $measurement['value'].($measurement['unit'] === null ? '' : ' '.$measurement['unit']) }}</td>
                            </tr>
                        @endif
                    @endforeach
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
            :href="route('atelier.probatio')"
            label="Comprendre l’essai de cet archet"
            inline
        />
    </x-site.section>
@endsection
