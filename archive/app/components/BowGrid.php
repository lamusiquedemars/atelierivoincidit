<?php
/**
 * Grille d'archets.
 * Attend un tableau $bows préparé par la couche data.
 * Ce composant ne connaît pas la base de données.
 */

if (empty($bows)) {
    return;
}
?>

<div class="grid grid--3 arcus-grid">
    <?php foreach ($bows as $bow): ?>
        <?php require app_path('components/BowCard.php'); ?>
    <?php endforeach; ?>
</div>