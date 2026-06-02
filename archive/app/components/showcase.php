<?php
/**
 * Showcase
 *
 * Composant visuel générique.
 * Ne gère ni <section>, ni .container.
 *
 * Props :
 * - title
 * - intro
 * - items
 * - layout : grid | carousel | featured
 * - class
 * - itemClass
 * - lightbox
 */

$showcaseTitle = $props['title'] ?? null;
$intro = $props['intro'] ?? null;
$items = $props['items'] ?? [];
$layout = $props['layout'] ?? 'grid';
$class = $props['class'] ?? '';
$itemClass = $props['itemClass'] ?? '';
$globalLightbox = $props['lightbox'] ?? false;

if (empty($items)) {
    return;
}

$allowedLayouts = ['grid', 'carousel', 'featured'];

if (!in_array($layout, $allowedLayouts, true)) {
    $layout = 'grid';
}

$isCarousel = $layout === 'carousel';

$showcaseClass = trim("showcase showcase--{$layout} {$class}");
$itemsClass = trim("showcase__items" . ($isCarousel ? " f-carousel" : ""));
$galleryId = 'showcase-' . substr(md5(($showcaseTitle ?? 'showcase') . serialize($items)), 0, 8);
?>

<div class="<?= e($showcaseClass) ?>">
    <?php if ($showcaseTitle || $intro): ?>
        <header class="showcase__header">
            <?php if ($showcaseTitle): ?>
                <h2 class="showcase__title"><?= e($showcaseTitle) ?></h2>
            <?php endif; ?>

            <?php if ($intro): ?>
                <p class="showcase__intro"><?= e($intro) ?></p>
            <?php endif; ?>
        </header>
    <?php endif; ?>

    <div
        class="<?= e($itemsClass) ?>"
        <?php if ($isCarousel): ?>
            data-showcase-carousel
        <?php endif; ?>
    >
        <?php foreach ($items as $item): ?>
            <?php
            $image = $item['image'] ?? '';
            $alt = $item['alt'] ?? ($item['title'] ?? '');
            $itemTitle = $item['title'] ?? '';
            $meta = $item['meta'] ?? '';
            $text = $item['text'] ?? '';
            $href = $item['href'] ?? '';
            $linkLabel = $item['linkLabel'] ?? 'Découvrir';

            // La lightbox peut être activée globalement ou item par item.
            $itemLightbox = $item['lightbox'] ?? $globalLightbox;

            $finalItemClass = trim(
                "showcase__item {$itemClass}" . ($isCarousel ? " f-carousel__slide" : "")
            );
            ?>

            <article class="<?= e($finalItemClass) ?>">
                <?php if ($image): ?>
                    <div class="showcase__media">
                        <?php if ($itemLightbox): ?>
                            <a
                                href="<?= e(img($image)) ?>"
                                data-fancybox="<?= e($galleryId) ?>"
                                data-caption="<?= e($itemTitle) ?>"
                            >
                                <img
                                    src="<?= e(img($image)) ?>"
                                    alt="<?= e($alt) ?>"
                                    loading="lazy"
                                >
                            </a>
                        <?php else: ?>
                            <img
                                src="<?= e(img($image)) ?>"
                                alt="<?= e($alt) ?>"
                                loading="lazy"
                            >
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="showcase__content">
                    <?php if ($meta): ?>
                        <p class="showcase__meta"><?= e($meta) ?></p>
                    <?php endif; ?>

                    <?php if ($itemTitle): ?>
                        <h3 class="showcase__item-title"><?= e($itemTitle) ?></h3>
                    <?php endif; ?>

                    <?php if ($text): ?>
                        <p class="showcase__text"><?= e($text) ?></p>
                    <?php endif; ?>

                    <?php if ($href): ?>
                        <a class="showcase__link" href="<?= e(url($href)) ?>">
                            <?= e($linkLabel) ?>
                        </a>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>