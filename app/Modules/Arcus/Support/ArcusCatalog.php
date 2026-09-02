<?php

namespace App\Modules\Arcus\Support;

use App\Modules\Arcus\Models\Bow;
use Illuminate\Support\Collection;

class ArcusCatalog
{
    public static function ranges(): array
    {
        return [
            'ars-antiqua' => [
                'title' => 'Ars Antiqua',
                'seoTitle' => 'Archets baroques et musiques anciennes | Ivo Incidit',
                'seoDescription' => 'Ars Antiqua réunit des archets baroques et des archets pour musiques anciennes, fabriqués artisanalement près de Lyon par Ivo Correia de Melo.',
                'description' => 'Archets inspirés des musiques anciennes : baroque, transition, musique de danse, 
        phrasé articulé. Plus qu’une reconstitution, 
            c’est une exploration de la sensibilité ancienne.',
                'heroSubtitle' => 'Pour retrouver un geste, une articulation, un répertoire.',
                'introTitle' => 'Un archet pour les musiques anciennes',
                'introText' => 'Ars Antiqua réunit des archets inspirés par les jeux anciens, le baroque et les équilibres plus légers ou plus articulés, selon les modèles. Il ne s’agit pas de promettre des reconstitutions historiques, mais de proposer des archets utiles au geste musical.',
                'intentionTitle' => 'Intention de jeu',
                'intentionText' => 'Cette série met l’accent sur l’articulation, la respiration de la phrase, la souplesse de réponse et le rapport direct à la corde. Ici, le mot important n’est pas seulement “ancien” : c’est le geste.',
                'forWhoTitle' => 'Pour qui ?',
                'forWhoItems' => [
                    'Musicien baroque ou curieux du jeu ancien.',
                    'Violoniste, altiste ou violoncelliste qui cherche une réponse différente de l’archet moderne.',
                    'Amateur ou professionnel qui veut explorer un autre rapport à la corde.',
                ],
                'notTitle' => 'Ce que cette série n’est pas',
                'notText' => 'Ars Antiqua ne désigne pas des archets modernes simplement ayant une esthétique “à l’ancienne”. Certains modèles peuvent être libres dans leur inspiration, d’autres plus proches d’un usage historique, mais tous sont pensés comme des archets de jeu pour un répertoire spécifique.',
                'availableTitle' => 'Archets Ars Antiqua disponibles',
                'emptyText' => 'Les archets Ars Antiqua sont fabriqués selon les disponibilités et les recherches en cours. Vous pouvez me contacter pour parler d’un besoin précis ou d’un prochain archet.',
                'finalTitle' => 'Le geste confirme',
                'finalText' => 'Pour ce type d’archet, les mots donnent une direction ; le geste confirme.',
                'image' => '/assets/images/archets-antiqua.jpeg',
            ],
            'ars-classica' => [
                'title' => 'Ars Classica',
                'seoTitle' => 'Archets modernes d’inspiration française | Ivo Incidit',
                'seoDescription' => 'Ars Classica réunit des archets modernes artisanaux inspirés de l’archèterie française, pensés pour l’étude, l’orchestre et la pratique professionnelle.',
                'description' => 'Archets inspirés de l’héritage de l’archèterie française : équilibre, stabilité, précision et projection.
        Les matériaux sont choisis selon des critères 
        classiques. C’est un archet pensé pour un usage professionnel, 
        respectant l’esprit de l’artisanat français.',
                'heroSubtitle' => 'Un archet équilibré, fait pour le travail musical quotidien.',
                'introTitle' => 'Un archet pour le jeu courant',
                'introText' => 'Ars Classica réunit des archets pensés pour l’étude avancée, la pratique professionnelle, l’enseignement, la musique de chambre, l’orchestre et le travail quotidien.',
                'intentionTitle' => 'Intention de jeu',
                'intentionText' => 'Cette série s’inscrit dans les standards de l’archèterie conventionnelle, avec des proportions, un équilibre et un montage proches des repères traditionnels.',
                'forWhoTitle' => 'Pour qui ?',
                'forWhoItems' => [
                    'Étudiant avancé qui sent que son archet actuel le limite.',
                    'Amateur engagé qui cherche un archet sérieux et durable.',
                    'Professionnel, enseignant ou musicien régulier qui veut un outil fiable.',
                ],
                'notTitle' => 'Ce que cette série n’est pas',
                'notText' => 'Ars Classica n’est pas une série de rupture ou d’expérimentation assumée. Elle reste proche des repères traditionnels de l’archèterie.',
                'availableTitle' => 'Archets Ars Classica disponibles',
                'emptyText' => 'Il n’y a pas toujours un archet Ars Classica disponible immédiatement. Vous pouvez me contacter pour connaître les prochaines fabrications ou me décrire ce que vous cherchez.',
                'finalTitle' => 'Choisir avec l’instrument',
                'finalText' => 'Un archet se choisit avec l’instrument. L’essai permet de vérifier la réponse, l’équilibre et le confort réel.',
                'image' => '/assets/images/archets-classica.jpeg',
            ],
            'ars-nova' => [
                'title' => 'Ars Nova',
                'seoTitle' => 'Archets contemporains | Atelier Ivo Incidit',
                'seoDescription' => 'Ars Nova rassemble des archets contemporains artisanaux qui explorent les bois brésiliens, l’équilibre et le caractère au service du jeu.',
                'description' => 'L’art nouveau, l’expression de mon identité. J’explore les propriétés du bois, les dimensions et les couleurs,
        toujours pensant au jeu professionnel.
        Ces archets incarnent ma vision contemporaine de l’archèterie.
        Pour les musiciens qui cherchent un archet unique.',
                'heroSubtitle' => 'Une nouvelle esthétique de l’archet, toujours pensée pour le jeu.',
                'introTitle' => 'La nouvelle voie',
                'introText' => 'Ars Nova rassemble des archets où la recherche de matière, d’équilibre et de caractère est plus visible. Ce sont des archets faits pour des musiciens ouverts à une proposition moins standard, mais toujours pensée pour le jeu.',
                'intentionTitle' => 'Intention de jeu',
                'intentionText' => 'Cette série explore des sensations plus personnelles : une réponse particulière, une couleur, un équilibre moins formaté, une présence différente dans la main.',
                'forWhoTitle' => 'Pour qui ?',
                'forWhoItems' => [
                    'Amateur engagé qui veut un archet avec une présence particulière.',
                    'Musicien curieux des bois et des équilibres moins conventionnels.',
                    'Professionnel ou enseignant qui cherche un outil complémentaire.',
                ],
                'notTitle' => 'Ce que cette série n’est pas',
                'notText' => 'Ars Nova n’est pas une réponse contre l’archet traditionnel. C’est une série construite autour d’autres équilibres de jeu, de son et de matière.',
                'availableTitle' => 'Archets Ars Nova disponibles',
                'emptyText' => 'Les archets Ars Nova sont fabriqués en petites séries ou en pièces uniques. Vous pouvez me contacter pour connaître les prochains archets ou me parler d’une recherche particulière.',
                'finalTitle' => 'Essayer pour juger',
                'finalText' => 'Ars Nova se comprend souvent mieux en main que sur une fiche. L’essai permet de sentir si cette voix d’archet correspond à votre jeu.',
                'image' => '/assets/images/archets-nova.jpeg',
            ],
        ];
    }

    public static function seriesCards(): array
    {
        return collect(self::ranges())->map(fn (array $range, string $slug) => [
            'title' => $range['title'],
            'text' => $range['description'],
            'image' => $range['image'],
            'alt' => 'Archet de la série '.$range['title'],
            'href' => route('arcus.range', $slug),
            'linkLabel' => 'Voir '.$range['title'],
        ])->values()->all();
    }

    public static function range(string $slug): ?array
    {
        return self::ranges()[$slug] ?? null;
    }

    public static function bowsByRange(string $rangeSlug, ?string $instrument = null): Collection
    {
        $query = Bow::query()
            ->with(['range', 'instrument', 'size', 'wood', 'color', 'images.media'])
            ->where('active', true)
            ->whereHas('range', fn ($query) => $query->where('slug', $rangeSlug))
            ->orderBy('code');

        if ($instrument !== null && trim($instrument) !== '') {
            $query->whereHas('instrument', fn ($query) => $query->where('name', trim($instrument)));
        }

        return $query->get()->map(fn (Bow $bow): array => self::prepareBowCard($bow));
    }

    public static function bowByCode(string $code): ?array
    {
        $bow = Bow::query()
            ->with(self::bowRelations())
            ->where('code', strtolower(trim($code)))
            ->where('active', true)
            ->first();

        return $bow ? self::bowData($bow) : null;
    }

    public static function galleryImages(string $code): Collection
    {
        $bow = Bow::query()->where('code', strtolower(trim($code)))->first();

        if (! $bow) {
            return collect();
        }

        return $bow->images()->with('media')->get()->map(fn ($image) => (object) [
            'image_path' => $image->media->url(),
            'resolved_image_url' => $image->media->url(),
            'alt' => $image->media->alt_text ?: 'Archet '.strtoupper($bow->code),
            'caption' => $image->caption ?: $image->media->caption,
            'title' => $image->media->display_name,
            'credit' => $image->media->credit,
            'width' => $image->media->width,
            'height' => $image->media->height,
        ]);
    }

    public static function priceData(array $bow): ?array
    {
        if (($bow['status'] ?? '') === 'sold' || empty($bow['price'])) {
            return null;
        }

        $price = (int) $bow['price'];
        $discount = (int) ($bow['discount'] ?? 0);

        if ($discount > 0) {
            return [
                'current' => $price - (int) round(($price * $discount) / 100),
                'old' => $price,
                'has_discount' => true,
            ];
        }

        return [
            'current' => $price,
            'old' => null,
            'has_discount' => false,
        ];
    }

    public static function formatPrice(int $priceInCents): string
    {
        return number_format($priceInCents / 100, 2, ',', ' ').' €';
    }

    public static function statusLabel(?string $status): string
    {
        return match ($status) {
            'sold' => 'Vendu',
            'unavailable' => 'Indisponible',
            'reserved' => 'En essai',
            'available' => 'Disponible',
            default => '',
        };
    }

    protected static function prepareBowCard(Bow $bow): array
    {
        $title = trim(implode(' ', array_filter([
            $bow->range?->name,
            $bow->id ? 'n°'.$bow->id : null,
            $bow->name ? '"'.$bow->name.'"' : null,
        ])));

        return [
            'title' => $title,
            'meta' => implode(' · ', array_filter([$bow->instrument?->name, $bow->size?->name])),
            'text' => implode("\n", array_filter([$bow->wood?->name, $bow->color?->name])),
            'image' => $bow->main_image_url ?: '',
            'alt' => self::altText(self::bowData($bow)),
            'priceData' => self::priceData($bow->toArray()),
            'statusLabel' => self::statusLabel($bow->status),
            'href' => route('arcus.show', strtolower($bow->code)),
            'ctaLabel' => 'Voir le détail de cet archet',
        ];
    }

    /** @return array<int, string> */
    protected static function bowRelations(): array
    {
        return [
            'range', 'instrument', 'style', 'shape', 'size', 'wood', 'origin', 'color',
            'buttonMaterial', 'frogMaterial', 'slideMaterial', 'tipMaterial', 'garnish',
            'flexibility', 'responsiveness', 'handling', 'naturalPressure', 'tone',
            'projection', 'sustain', 'articulation',
        ];
    }

    protected static function bowData(Bow $bow): array
    {
        return [
            ...$bow->attributesToArray(),
            'stick_weight_display' => self::formatMeasure($bow->stick_weight),
            'total_weight_display' => self::formatMeasure($bow->total_weight),
            'stick_length_display' => self::formatMeasure($bow->stick_length),
            'total_length_display' => self::formatMeasure($bow->total_length),
            'balance_point_display' => self::formatMeasure($bow->balance_point),
            'density_display' => self::formatMeasure($bow->density),
            'speed_display' => self::formatMeasure($bow->speed),
            'elasticity_display' => self::formatMeasure($bow->elasticity, 1),
            'frequency_display' => self::formatMeasure($bow->frequency),
            'damping_display' => self::formatMeasure($bow->damping, 3),
            'atelier_name' => $bow->name,
            'range_name' => $bow->range?->name,
            'range_slug' => $bow->range?->slug,
            'instrument_name' => $bow->instrument?->name,
            'style_name' => $bow->style?->name,
            'shape_name' => $bow->shape?->name,
            'size_name' => $bow->size?->name,
            'wood_name' => $bow->wood?->name,
            'origin_name' => $bow->origin?->name,
            'color_name' => $bow->color?->name,
            'button_material_name' => $bow->buttonMaterial?->name,
            'frog_material_name' => $bow->frogMaterial?->name,
            'slide_material_name' => $bow->slideMaterial?->name,
            'tip_material_name' => $bow->tipMaterial?->name,
            'garnish_name' => $bow->garnish?->name,
            'flexibility_name' => $bow->flexibility?->name,
            'responsiveness_name' => $bow->responsiveness?->name,
            'handling_name' => $bow->handling?->name,
            'natural_pressure_name' => $bow->naturalPressure?->name,
            'tone_name' => $bow->tone?->name,
            'projection_name' => $bow->projection?->name,
            'sustain_name' => $bow->sustain?->name,
            'articulation_name' => $bow->articulation?->name,
        ];
    }

    private static function formatMeasure(mixed $value, int $decimals = 0): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return number_format((float) $value, $decimals, '.', '');
    }

    protected static function altText(array $bow): string
    {
        return implode(' ', array_filter([
            'Archet',
            $bow['range_name'] ?? null,
            $bow['instrument_name'] ?? null,
            $bow['size_name'] ?? null,
        ]));
    }
}
