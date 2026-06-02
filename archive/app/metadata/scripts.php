<?php if (!empty($SITE['tracking']['ga_id'])): ?>
  <?php $gaId = $SITE['tracking']['ga_id']; ?>
  <!-- Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($gaId) ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', <?= json_encode($gaId) ?>);
  </script>
<?php endif; ?>

<!-- Icônes Lucide -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
  document.addEventListener("DOMContentLoaded", () => {
    if (window.lucide) {
      lucide.createIcons();
    }
  });
</script>
<!-- Fancybox (pour les galeries) -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/carousel/carousel.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/carousel/carousel.arrows.umd.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/carousel/carousel.dots.umd.js"></script>
<!-- Chargement des fonctions JS du site -->
<script src="<?= e(asset('js/site.js')) ?>"></script>