<?php
/**
 * FeatureGrid
 *
 * Affiche une grille de cartes éditoriales.
 * Ne contient ni <section> ni .container.
 */

$items = $props['items'] ?? [];
$variant = $props['variant'] ?? 'default';
$columns = $props['columns'] ?? 3;
$class = $props['class'] ?? '';

if (empty($items)) {
    return;
}

$classes = trim('feature-grid feature-grid--' . $variant . ' grid grid--' . $columns . ' ' . $class);
?>

<div class="<?= e($classes) ?>">
    <?php foreach ($items as $item): ?>
        <?php render('FeatureCard', [
            'item' => $item,
            'variant' => $variant,
        ]); ?>
    <?php endforeach; ?>
</div>