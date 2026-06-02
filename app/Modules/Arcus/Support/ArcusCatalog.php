<?php

namespace App\Modules\Arcus\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ArcusCatalog
{
    public static function ranges(): array
    {
        return [
            'ars-antiqua' => [
                'title' => 'Ars Antiqua',
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
        $query = self::baseBowQuery()
            ->where('r.slug', $rangeSlug)
            ->orderBy('b.code');

        if ($instrument !== null && trim($instrument) !== '') {
            $query->where('i.name', trim($instrument));
        }

        return $query->get()->map(fn ($bow) => self::prepareBowCard((array) $bow));
    }

    public static function bowByCode(string $code): ?array
    {
        $bow = self::baseBowQuery()
            ->addSelect([
                'b.notes',
                'b.stick_weight',
                'b.total_weight',
                'b.stick_length',
                'b.total_length',
                'b.balance_point',
                'b.density',
                'b.speed',
                'b.elasticity',
                'b.frequency',
                'b.damping',
                'o.name as origin_name',
            ])
            ->leftJoin('origin as o', 'b.origin_id', '=', 'o.id')
            ->where('b.code', trim($code))
            ->first();

        return $bow ? (array) $bow : null;
    }

    public static function galleryImages(string $code): Collection
    {
        $code = strtolower(trim($code));
        $dir = public_path('assets/images/archets/'.$code);

        if ($code === '' || ! File::isDirectory($dir)) {
            return collect();
        }

        return collect(File::glob($dir.'/*.{jpg,jpeg,png,webp,heic}', GLOB_BRACE))
            ->sort()
            ->values()
            ->map(fn (string $path) => (object) [
                'image_path' => '/assets/images/archets/'.$code.'/'.basename($path),
                'alt' => 'Archet '.$code,
                'caption' => null,
                'title' => null,
                'credit' => null,
                'width' => 1600,
                'height' => 1000,
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

    protected static function baseBowQuery()
    {
        return DB::connection('legacy')
            ->table('bow as b')
            ->select([
                'b.id',
                'b.code',
                'b.name as atelier_name',
                'b.status',
                'b.price',
                'b.discount',
                'b.short_trait',
                'i.name as instrument_name',
                's.name as style_name',
                'sh.name as shape_name',
                'w.name as wood_name',
                'c.name as color_name',
                'fm.name as frog_material_name',
                'sm.name as slide_material_name',
                'bm.name as button_material_name',
                'tm.name as tip_material_name',
                'g.name as garnish_name',
                'sz.name as size_name',
                'r.name as range_name',
                'r.slug as range_slug',
                'q1.name as flexibility_name',
                'q2.name as responsiveness_name',
                'q3.name as handling_name',
                'q4.name as natural_pressure_name',
                'q5.name as tone_name',
                'q6.name as projection_name',
                'q7.name as sustain_name',
                'q8.name as articulation_name',
            ])
            ->leftJoin('instrument as i', 'b.instrument_id', '=', 'i.id')
            ->leftJoin('style as s', 'b.style_id', '=', 's.id')
            ->leftJoin('shape as sh', 'b.shape_id', '=', 'sh.id')
            ->leftJoin('wood as w', 'b.wood_id', '=', 'w.id')
            ->leftJoin('color as c', 'b.color_id', '=', 'c.id')
            ->leftJoin('material as fm', 'b.frog_material_id', '=', 'fm.id')
            ->leftJoin('material as sm', 'b.slide_material_id', '=', 'sm.id')
            ->leftJoin('material as bm', 'b.button_material_id', '=', 'bm.id')
            ->leftJoin('material as tm', 'b.tip_material_id', '=', 'tm.id')
            ->leftJoin('garnish as g', 'b.garnish_id', '=', 'g.id')
            ->leftJoin('size as sz', 'b.size_id', '=', 'sz.id')
            ->leftJoin('range as r', 'b.range_id', '=', 'r.id')
            ->leftJoin('quality as q1', 'b.flexibility_id', '=', 'q1.id')
            ->leftJoin('quality as q2', 'b.responsiveness_id', '=', 'q2.id')
            ->leftJoin('quality as q3', 'b.handling_id', '=', 'q3.id')
            ->leftJoin('quality as q4', 'b.natural_pressure_id', '=', 'q4.id')
            ->leftJoin('quality as q5', 'b.tone_id', '=', 'q5.id')
            ->leftJoin('quality as q6', 'b.projection_id', '=', 'q6.id')
            ->leftJoin('quality as q7', 'b.sustain_id', '=', 'q7.id')
            ->leftJoin('quality as q8', 'b.articulation_id', '=', 'q8.id')
            ->where('b.active', true);
    }

    protected static function prepareBowCard(array $bow): array
    {
        $title = trim(implode(' ', array_filter([
            $bow['range_name'] ?? null,
            ! empty($bow['id']) ? 'n°'.$bow['id'] : null,
            ! empty($bow['atelier_name']) ? '"'.$bow['atelier_name'].'"' : null,
        ])));

        $code = strtolower((string) ($bow['code'] ?? ''));

        return [
            'title' => $title,
            'meta' => implode(' · ', array_filter([$bow['instrument_name'] ?? null, $bow['size_name'] ?? null])),
            'text' => implode("\n", array_filter([$bow['wood_name'] ?? null, $bow['color_name'] ?? null])),
            'image' => self::mainImage($code),
            'alt' => self::altText($bow),
            'priceData' => self::priceData($bow),
            'statusLabel' => self::statusLabel($bow['status'] ?? null),
            'href' => route('arcus.show', $code),
            'ctaLabel' => 'Voir le détail de cet archet',
        ];
    }

    protected static function mainImage(string $code): string
    {
        $dir = public_path('assets/images/archets/'.$code);

        if ($code === '' || ! File::isDirectory($dir)) {
            return '';
        }

        $mainImages = File::glob($dir.'/main.{jpg,jpeg,png,webp,heic}', GLOB_BRACE);
        $images = $mainImages ?: File::glob($dir.'/*.{jpg,jpeg,png,webp,heic}', GLOB_BRACE);

        return empty($images) ? '' : '/assets/images/archets/'.$code.'/'.basename($images[0]);
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
