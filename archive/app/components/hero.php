<?php
// ==========================
// HERO COMPONENT
// ==========================
// Texte principal (titre)
$title = $props['title'] ?? '';
// Sous-titre / description
$subtitle = $props['subtitle'] ?? '';
// Lien du bouton (optionnel)
$link = $props['link'] ?? null;
// Texte du bouton
$label = $props['label'] ?? 'Voir';

// Classes additionnelles passées depuis le render()
// ex: ['hero--page', 'hero--center']
$class = $props['class'] ?? '';
// Construction des classes finales : base obligatoire + classes optionnelles
$classes = trim("page-hero $class");
?>

<section class="<?= e($classes) ?>">
  <div class="container">
    <div class="hero__content">
      <!-- TITRE -->
      <?php if ($title): ?>
        <h1 class="hero__title">
          <?= e($title) ?>
        </h1>
      <?php endif; ?>
      <!-- SUBTITLE -->
      <?php if ($subtitle): ?>
        <p>
          <?= e($subtitle) ?>
        </p>
      <?php endif; ?>
      <!-- CTA -->
      <?php if ($link): ?>
        <div class="hero__actions">
          <a href="<?= e($link) ?>" class="btn btn-primary">
            <?= e($label) ?>
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>