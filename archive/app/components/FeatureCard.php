<?php
/**
 * FeatureCard
 *
 * Affiche une carte image + titre + texte + lien.
 */

$item = $props['item'] ?? [];
$variant = $props['variant'] ?? 'default';

$title = $item['title'] ?? '';
$text = $item['text'] ?? '';
$image = $item['image'] ?? '';
$alt = $item['alt'] ?? '';
$href = $item['href'] ?? '';
$linkLabel = $item['linkLabel'] ?? '';

if ($title === '' && $text === '' && $image === '') {
    return;
}
?>

<article class="feature-card feature-card--<?= e($variant) ?>">
    <?php if ($href !== ''): ?>
        <a class="feature-card__link" href="<?= e($href) ?>">
    <?php endif; ?>
        <?php if ($image !== ''): ?>
            <img class="feature-card__image" src="<?= e($image) ?>" alt="<?= e($alt) ?>">
        <?php endif; ?>
        <?php if ($title !== ''): ?>
            <h3 class="feature-card__title"><?= e($title) ?></h3>
        <?php endif; ?>
        <?php if ($text !== ''): ?>
            <p class="feature-card__text"><?= e($text) ?></p>
        <?php endif; ?>
        <?php if ($linkLabel !== ''): ?>
            <span class="feature-card__cta"><?= e($linkLabel) ?></span>
        <?php endif; ?>
    <?php if ($href !== ''): ?>
        </a>
    <?php endif; ?>
</article>