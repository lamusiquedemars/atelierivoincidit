<?php
/**
 * Composant Quote Carousel.
 *
 * Affiche des citations une par une, avec navigation manuelle.
 * Le composant ne contient volontairement ni <section> ni .container.
 *
 * Props disponibles :
 * - kicker : petit surtitre optionnel
 * - title : titre optionnel
 * - intro : texte introductif optionnel
 * - variant : variante visuelle, ex. editorial, cards, compact
 * - class : classe CSS additionnelle
 * - items : liste des citations
 */

$kicker = $props['kicker'] ?? '';
$title = $props['title'] ?? '';
$intro = $props['intro'] ?? '';
$variant = $props['variant'] ?? 'editorial';
$class = $props['class'] ?? '';
$items = $props['items'] ?? [];

$items = array_values(array_filter($items, function ($item) {
    return is_array($item)
        && !empty(trim((string) ($item['quote'] ?? '')));
}));
if (count($items) === 0) {
    return;
}

$classes = trim('quote-carousel quote-carousel--' . $variant . ' ' . $class);
?>
<div class="<?= e($classes) ?>">
    <?php if ($kicker !== '' || $title !== '' || $intro !== '') : ?>
        <div class="quote-carousel__header">
            <?php if ($kicker !== '') : ?>
                <p class="quote-carousel__kicker"><?= e($kicker) ?></p>
            <?php endif; ?>
            <?php if ($title !== '') : ?>
                <h2 class="quote-carousel__title"><?= e($title) ?></h2>
            <?php endif; ?>
            <?php if ($intro !== '') : ?>
                <p class="quote-carousel__intro"><?= e($intro) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="f-carousel quote-carousel__carousel" data-quote-carousel>
        <?php foreach ($items as $item) : ?>
            <blockquote class="f-carousel__slide quote-carousel__slide">
                <p class="quote-carousel__quote">
                    « <?= e($item['quote']) ?> »
                </p>

                <?php if (!empty($item['author']) || !empty($item['meta'])) : ?>
                    <footer class="quote-carousel__footer">
                        <?php if (!empty($item['author'])) : ?>
                            <span class="quote-carousel__author"><?= e($item['author']) ?></span>
                        <?php endif; ?>

                        <?php if (!empty($item['meta'])) : ?>
                            <span class="quote-carousel__meta"><?= e($item['meta']) ?></span>
                        <?php endif; ?>
                    </footer>
                <?php endif; ?>
            </blockquote>
        <?php endforeach; ?>
    </div>
</div>