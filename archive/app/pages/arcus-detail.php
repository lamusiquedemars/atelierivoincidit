<?php

/**
 * Page détail d’un archet.
 *
 * URL : /arcus/{code}
 * Le code est transmis par le routeur dans $params['code'].
 * Cette page ne contient aucune requête SQL.
 */

require_once app_path('data/bows.php');

$code = $params['code'] ?? '';
$bow = get_bow_by_code($code);

if ($bow === null) {
    http_response_code(404);
?>

    <section class="section">
        <div class="container">
            <h1>Archet introuvable</h1>
            <p>Cet archet n’est pas disponible ou n’existe pas.</p>
            <p class="cta">
                <a class="btn" href="<?= e(url('/arcus')) ?>">
                    Retour aux archets
                </a>
            </p>
        </div>
    </section>

<?php
    return;
}

$photos = get_bow_gallery_images($bow['code']);

$priceData = get_bow_price_data($bow);
$statusLabel = get_bow_status_label($bow['status'] ?? '');
$statusClass = get_bow_status_class($bow['status'] ?? '');

$bowTitle = trim(
    ($bow['range_name'] ?? '') .
        ' “' .
        (!empty($bow['atelier_name']) ? $bow['atelier_name'] : 'n° ' . $bow['id']) .
        '”'
);

$bowSubtitleParts = array_filter([
    'Archet ' . ($bow['style_name'] ?? ''),
    $bow['instrument_name'] ?? '',
    $bow['size_name'] ?? '',
]);

$bowSubtitle = implode(' · ', $bowSubtitleParts);

render('hero', [
    'title' => $bowTitle,
    'subtitle' => $bowSubtitle,
    'class' => ['hero-arcus'],
]);
?>

<section class="section">
    <div class="container">
        <p class="price">
            <?php if ($priceData !== null): ?>
                <?php if ($priceData['has_discount']): ?>
                    <span class="price-new"><?= e(format_price($priceData['current'])) ?></span>
                    <span class="price-old"><?= e(format_price($priceData['old'])) ?></span>
                <?php else: ?>
                    <span class="price-std"><?= e(format_price($priceData['current'])) ?></span>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($statusLabel !== ''): ?>
                <span class="badge <?= e($statusClass) ?>">
                    <?= e($statusLabel) ?>
                </span>
            <?php endif; ?>
        </p>

        <?php if (!empty($bow['short_trait'])): ?>
            <p>
                <?= e($bow['short_trait']) ?>
            </p>
        <?php endif; ?>
    </div>
</section>

<?php if (!empty($photos)): ?>
    <section class="section">
        <div class="container">
            <div class="bloc photos">
                <?php
                $galleryItems = array_map(
                    fn($photo) => [
                        'image' => $photo,
                        'alt' => get_bow_alt_text($bow),
                        'lightbox' => true,
                    ],
                    $photos
                );

                render('showcase', [
                    'items' => $galleryItems,
                    'layout' => 'carousel',
                    'lightbox' => true,
                ]);
                ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<section class="section">
    <div class="container">
        <div class="grid grid--3">
            <article class="card">
                <h2>Caractère de jeu</h2>
                <ul>
                    <li><strong>Flexibilité :</strong> <?= e($bow['flexibility_name'] ?? '') ?></li>
                    <li><strong>Réactivité :</strong> <?= e($bow['responsiveness_name'] ?? '') ?></li>
                    <li><strong>Maniabilité :</strong> <?= e($bow['handling_name'] ?? '') ?></li>
                    <li><strong>Pression naturelle :</strong> <?= e($bow['natural_pressure_name'] ?? '') ?></li>
                </ul>
                <h2>Sonorité</h2>
                <ul>
                    <li><strong>Timbre dominant :</strong> <?= e($bow['tone_name'] ?? '') ?></li>
                    <li><strong>Projection :</strong> <?= e($bow['projection_name'] ?? '') ?></li>
                    <li><strong>Sustain :</strong> <?= e($bow['sustain_name'] ?? '') ?></li>
                    <li><strong>Articulation :</strong> <?= e($bow['articulation_name'] ?? '') ?></li>
                </ul>
            </article>
            <article class="bloc">
                <h2>Fabrication & matériaux</h2>
                <ul>
                    <li><strong>Instrument :</strong> <?= e($bow['instrument_name'] ?? '') ?></li>
                    <li><strong>Taille :</strong> <?= e($bow['size_name'] ?? '') ?></li>
                    <li><strong>Style :</strong> <?= e($bow['style_name'] ?? '') ?></li>
                    <li><strong>Forme :</strong> <?= e($bow['shape_name'] ?? '') ?></li>
                    <li>
                        <strong>Bois :</strong>
                        <?= e($bow['wood_name'] ?? '') ?>
                        <?php if (!empty($bow['origin_name'])): ?>
                            — <?= e($bow['origin_name']) ?>
                        <?php endif; ?>
                    </li>
                    <li><strong>Couleur :</strong> <?= e($bow['color_name'] ?? '') ?></li>
                    <li><strong>Hausse :</strong> <?= e($bow['frog_material_name'] ?? '') ?></li>
                    <li><strong>Coulisse :</strong> <?= e($bow['slide_material_name'] ?? '') ?></li>
                    <li><strong>Bouton :</strong> <?= e($bow['button_material_name'] ?? '') ?></li>
                    <li><strong>Pointe :</strong> <?= e($bow['tip_material_name'] ?? '') ?></li>
                    <li><strong>Garniture :</strong> <?= e($bow['garnish_name'] ?? '') ?></li>
                </ul>
            </article>
            <article class="card">
                <h2>Mesures d’atelier</h2>
                <table>
                    <tr>
                        <th>Poids baguette</th>
                        <td><?= e($bow['stick_weight'] ?? '') ?> g</td>
                    </tr>
                    <tr>
                        <th>Poids total</th>
                        <td><?= e($bow['total_weight'] ?? '') ?> g</td>
                    </tr>
                    <tr>
                        <th>Longueur baguette</th>
                        <td><?= e($bow['stick_length'] ?? '') ?> mm</td>
                    </tr>
                    <tr>
                        <th>Longueur totale</th>
                        <td><?= e($bow['total_length'] ?? '') ?> mm</td>
                    </tr>
                    <tr>
                        <th>Équilibre</th>
                        <td><?= e($bow['balance_point'] ?? '') ?> mm</td>
                    </tr>
                    <tr>
                        <th>Densité</th>
                        <td><?= e($bow['density'] ?? '') ?> g/cm³</td>
                    </tr>
                    <tr>
                        <th>Vitesse du son</th>
                        <td><?= e($bow['speed'] ?? '') ?> m/s</td>
                    </tr>
                    <tr>
                        <th>Élasticité</th>
                        <td><?= e($bow['elasticity'] ?? '') ?> GPa</td>
                    </tr>
                    <tr>
                        <th>Fréquence</th>
                        <td><?= e($bow['frequency'] ?? '') ?> Hz</td>
                    </tr>
                    <tr>
                        <th>Amortissement</th>
                        <td><?= e($bow['damping'] ?? '') ?></td>
                    </tr>
                </table>
            </article>
        </div>
    </div>
</section>

<?php if (!empty($bow['notes'])): ?>
    <section class="section">
        <div class="container">
            <div class="bloc notes">
                <h2>Notes de l’archetier</h2>
                <p><?= nl2br(e($bow['notes'])) ?></p>
            </div>
        </div>
    </section>
<?php endif; ?>

<section class="section section--gradient">
    <div class="container">
        <h2>Essayer cet archet</h2>
        <p>
            Les mesures orientent, mais le choix se confirme surtout avec l’instrument,
            dans le geste et dans l’écoute.
        </p>
        <p class="cta">
            <a class="btn" href="<?= e(url('/contact')) ?>">
                Demander à essayer cet archet
            </a>
            <a class="btn" href="<?= e(url('/arcus')) ?>">
                Voir les autres archets
            </a>
        </p>
    </div>
</section>