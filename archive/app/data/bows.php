<?php

/**
 * Données des archets.
 *
 * Contient les fonctions de lecture liées aux archets.
 * Aucune sortie HTML ici.
 */

function get_bows_by_range(string $rangeSlug, ?string $instrument = null): array
{
    $sql = "
        SELECT
            b.id,
            b.code,
            b.name AS atelier_name,
            b.status,
            b.price,
            b.discount,
            b.short_trait,

            i.name AS instrument_name,
            s.name AS style_name,
            sh.name AS shape_name,
            w.name AS wood_name,
            c.name AS color_name,
            fm.name AS frog_material_name,
            sm.name AS slide_material_name,
            bm.name AS button_material_name,
            tm.name AS tip_material_name,
            g.name AS garnish_name,
            sz.name AS size_name,

            r.name AS range_name,
            r.slug AS range_slug,

            q1.name AS flexibility_name,
            q2.name AS responsiveness_name,
            q3.name AS handling_name,
            q4.name AS natural_pressure_name,
            q5.name AS tone_name,
            q6.name AS projection_name,
            q7.name AS sustain_name,
            q8.name AS articulation_name

        FROM bow b
        LEFT JOIN instrument i ON b.instrument_id = i.id
        LEFT JOIN style s ON b.style_id = s.id
        LEFT JOIN shape sh ON b.shape_id = sh.id
        LEFT JOIN wood w ON b.wood_id = w.id
        LEFT JOIN color c ON b.color_id = c.id
        LEFT JOIN material fm ON b.frog_material_id = fm.id
        LEFT JOIN material sm ON b.slide_material_id = sm.id
        LEFT JOIN material bm ON b.button_material_id = bm.id
        LEFT JOIN material tm ON b.tip_material_id = tm.id
        LEFT JOIN garnish g ON b.garnish_id = g.id
        LEFT JOIN size sz ON b.size_id = sz.id
        LEFT JOIN `range` r ON b.range_id = r.id
        LEFT JOIN quality q1 ON b.flexibility_id = q1.id
        LEFT JOIN quality q2 ON b.responsiveness_id = q2.id
        LEFT JOIN quality q3 ON b.handling_id = q3.id
        LEFT JOIN quality q4 ON b.natural_pressure_id = q4.id
        LEFT JOIN quality q5 ON b.tone_id = q5.id
        LEFT JOIN quality q6 ON b.projection_id = q6.id
        LEFT JOIN quality q7 ON b.sustain_id = q7.id
        LEFT JOIN quality q8 ON b.articulation_id = q8.id

        WHERE b.active = 1
        AND r.slug = :range_slug
    ";

    $params = [
        'range_slug' => $rangeSlug,
    ];

    if ($instrument !== null && $instrument !== '') {
        $sql .= " AND i.name = :instrument";
        $params['instrument'] = $instrument;
    }

    $sql .= " ORDER BY b.code ASC";

    $stmt = db()->prepare($sql);
    $stmt->execute($params);

    $bows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array_map('prepare_bow_card_item', $bows);
}

/**
 * Retourne un archet public à partir de son code unique.
 *
 * Utilisé pour la page détail : /arcus/{code}
 * Ne génère aucun HTML.
 */
function get_bow_by_code(string $code): ?array
{
    $code = trim($code);

    if ($code === '') {
        return null;
    }

    $sql = "
        SELECT
            b.*,
            b.name AS atelier_name,

            i.name AS instrument_name,
            s.name AS style_name,
            sh.name AS shape_name,
            w.name AS wood_name,
            c.name AS color_name,

            fm.name AS frog_material_name,
            sm.name AS slide_material_name,
            bm.name AS button_material_name,
            tm.name AS tip_material_name,

            g.name AS garnish_name,
            sz.name AS size_name,
            o.name AS origin_name,

            r.name AS range_name,
            r.slug AS range_slug,

            q1.name AS flexibility_name,
            q2.name AS responsiveness_name,
            q3.name AS handling_name,
            q4.name AS natural_pressure_name,
            q5.name AS tone_name,
            q6.name AS projection_name,
            q7.name AS sustain_name,
            q8.name AS articulation_name

        FROM bow b
        LEFT JOIN instrument i ON b.instrument_id = i.id
        LEFT JOIN style s ON b.style_id = s.id
        LEFT JOIN shape sh ON b.shape_id = sh.id
        LEFT JOIN wood w ON b.wood_id = w.id
        LEFT JOIN color c ON b.color_id = c.id

        LEFT JOIN material fm ON b.frog_material_id = fm.id
        LEFT JOIN material sm ON b.slide_material_id = sm.id
        LEFT JOIN material bm ON b.button_material_id = bm.id
        LEFT JOIN material tm ON b.tip_material_id = tm.id

        LEFT JOIN garnish g ON b.garnish_id = g.id
        LEFT JOIN size sz ON b.size_id = sz.id
        LEFT JOIN origin o ON b.origin_id = o.id

        LEFT JOIN `range` r ON b.range_id = r.id

        LEFT JOIN quality q1 ON b.flexibility_id = q1.id
        LEFT JOIN quality q2 ON b.responsiveness_id = q2.id
        LEFT JOIN quality q3 ON b.handling_id = q3.id
        LEFT JOIN quality q4 ON b.natural_pressure_id = q4.id
        LEFT JOIN quality q5 ON b.tone_id = q5.id
        LEFT JOIN quality q6 ON b.projection_id = q6.id
        LEFT JOIN quality q7 ON b.sustain_id = q7.id
        LEFT JOIN quality q8 ON b.articulation_id = q8.id

        WHERE b.active = 1
        AND b.code = :code
        LIMIT 1
    ";

    $stmt = db()->prepare($sql);
    $stmt->execute([
        'code' => $code,
    ]);

    $bow = $stmt->fetch(PDO::FETCH_ASSOC);

    return $bow ?: null;
}

/**
 * Retourne les images disponibles pour un archet.
 *
 * Cherche dans :
 * /public/assets/images/archets/{code}/
 *
 * Ne génère aucun HTML.
 */
function get_bow_gallery_images(string $code): array
{
    $code = strtolower(trim($code));

    if ($code === '') {
        return [];
    }
    $absoluteDir = public_path('assets/images/archets/' . $code);
    $relativeDir = 'archets/' . $code;
    if (!is_dir($absoluteDir)) {
        return [];
    }
    $images = glob($absoluteDir . '/*.{jpg,jpeg,png,webp,heic}', GLOB_BRACE);
    if (empty($images)) {
        return [];
    }
    sort($images);
    return array_map(
        fn ($image) => $relativeDir . '/' . basename($image),
        $images
    );
}

function prepare_bow_card_item(array $bow): array
{
    $titleParts = [];

    if (!empty($bow['range_name'])) {
        $titleParts[] = $bow['range_name'];
    }

    if (!empty($bow['id'])) {
        $titleParts[] = 'n°' . $bow['id'];
    }

    if (!empty($bow['atelier_name'])) {
        $titleParts[] = '“' . $bow['atelier_name'] . '”';
    }

    $instrumentParts = [];

    if (!empty($bow['instrument_name'])) {
        $instrumentParts[] = $bow['instrument_name'];
    }

    if (!empty($bow['size_name'])) {
        $instrumentParts[] = $bow['size_name'];
    }

    $metaParts = [];

    if (!empty($bow['wood_name'])) {
        $metaParts[] = $bow['wood_name'];
    }

    if (!empty($bow['shape_name'])) {
        $metaParts[] = $bow['shape_name'];
    }

    if (!empty($bow['garnish_name'])) {
        $metaParts[] = $bow['garnish_name'];
    }

    $textParts = [];

    if (!empty($bow['wood_name'])) {
        $textParts[] = $bow['wood_name'];
    }

    if (!empty($bow['color_name'])) {
        $textParts[] = $bow['color_name'];
    }

    return [
        'title' => trim(implode(' ', $titleParts)),
        'meta' => implode(' · ', array_filter($instrumentParts)),
        'text' => implode("\n", array_filter($textParts)),
        'image' => get_bow_main_image($bow['code'] ?? ''),
        'alt' => get_bow_alt_text($bow),
        'priceData' => get_bow_price_data($bow),
        'statusLabel' => get_bow_status_label($bow['status'] ?? ''),
        'statusClass' => get_bow_status_class($bow['status'] ?? ''),
        'href' => $bow['code'],
        'ctaLabel' => 'Voir le détail de cet archet',
    ];
}

function get_bow_main_image(string $code): string
{
    if ($code === '') {
        return '';
    }
    $code = strtolower($code);
    // Chemin disque réel
    $absoluteDir = public_path('assets/images/archets/' . $code);
    // Chemin web relatif à /assets/
    $assetDir = 'images/archets/' . $code;
    if (!is_dir($absoluteDir)) {
        return '';
    }
    $mainImages = glob($absoluteDir . '/main.{jpg,jpeg,png,webp,heic}', GLOB_BRACE);
    if (!empty($mainImages)) {
        return asset($assetDir . '/' . basename($mainImages[0]));
    }
    $images = glob($absoluteDir . '/*.{jpg,jpeg,png,webp,heic}', GLOB_BRACE);
    if (!empty($images)) {
        return asset($assetDir . '/' . basename($images[0]));
    }
    return '';
}

function get_bow_alt_text(array $bow): string
{
    $parts = ['Archet'];

    if (!empty($bow['range_name'])) {
        $parts[] = $bow['range_name'];
    }

    if (!empty($bow['instrument_name'])) {
        $parts[] = $bow['instrument_name'];
    }

    if (!empty($bow['size_name'])) {
        $parts[] = $bow['size_name'];
    }

    return implode(' ', $parts);
}

function get_bow_price_data(array $bow): ?array
{
    $status = $bow['status'] ?? '';
    if ($status === 'sold') {
        return null;
    }
    if (!isset($bow['price']) || $bow['price'] === null || $bow['price'] === '') {
        return null;
    }

    $price = (int) $bow['price'];
    $discount = isset($bow['discount']) ? (int) $bow['discount'] : 0;
    if ($discount > 0) {
        $finalPrice = $price - (int) round(($price * $discount) / 100);
        return [
            'current' => $finalPrice,
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

function format_price(int $priceInCents): string
{
    return number_format($priceInCents / 100, 2, ',', ' ') . ' €';
}

function get_bow_status_label(string $status): string
{
    return match ($status) {
        'sold' => 'Vendu',
        'unavailable' => 'Indisponible',
        'reserved' => 'En essai',
        'available' => 'Disponible',
        default => '',
    };
}

function get_bow_status_class(string $status): string
{
    return match ($status) {
        'sold' => 'sold',
        'unavailable' => 'unavailable',
        'reserved' => 'reserved',
        'available' => 'available',
        default => '',
    };
}
