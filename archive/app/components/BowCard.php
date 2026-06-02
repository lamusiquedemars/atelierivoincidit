<?php

/**
 * Carte d'archet.
 *
 * Attend un tableau $bow déjà préparé pour l'affichage.
 * Aucun accès SQL, aucun calcul métier lourd ici.
 */

$bowTitle = $bow['title'] ?? 'Archet';
$bowMeta = $bow['meta'] ?? '';
$bowText = $bow['text'] ?? '';
$bowImage = $bow['image'] ?? '';
$bowAlt = $bow['alt'] ?? $bowTitle;
$bowHref = $bow['href'] ?? '';
$bowStatus = $bow['statusLabel'] ?? '';
$bowStatusClass = $bow['statusClass'] ?? '';
$bowPriceData = $bow['priceData'] ?? null;
$bowCta = $bow['ctaLabel'] ?? 'Voir cet archet';
?>

<article class="card arcus-card">
    <figure class="arcus-card__media">
        <?php if ($bowImage !== ''): ?>
            <img class="arcus-card__image" src="<?= e($bowImage) ?>" alt="<?= e($bowAlt) ?>" loading="lazy">
        <?php else: ?>
            <div class="arcus-card__placeholder" aria-hidden="true"></div>
        <?php endif; ?>
    </figure>

    <div class="arcus-card__content">
        <h3 class="arcus-card__title"><?= e($bowTitle) ?></h3>

        <?php if ($bowMeta !== ''): ?>
            <p class="arcus-card__meta"><?= e($bowMeta) ?></p>
        <?php endif; ?>

        <?php if ($bowText !== ''): ?>
            <p class="arcus-card__text"><?= e($bowText) ?></p>
        <?php endif; ?>

        <?php if ($bowPriceData !== null): ?>
            <p class="bow-card__price">
                <span class="price">
                    <?= e(format_price($bowPriceData['current'])) ?>
                </span>

                <?php if ($bowPriceData['old'] !== null): ?>
                    <span class="price price-old">
                        <?= e(format_price($bowPriceData['old'])) ?>
                    </span>
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <?php if ($bowHref !== ''): ?>
            <p class="card-footer arcus-card__cta">
                <a class="btn" href="<?= e($bowHref) ?>">
                    <?= e($bowCta) ?>
                </a>
            </p>
        <?php else: ?>
            <p class="card-footer arcus-card__cta">
                <a class="btn" href="<?= e(url('/contact')) ?>">
                    <?= e($bowCta) ?>
                </a>
            </p>
        <?php endif; ?>
    </div>
</article>