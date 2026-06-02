<?php
// Variables disponibles
$rangeSlug  = $rangeSlug  ?? ($_GET['range'] ?? null);
$rangeName  = $rangeName  ?? null;
$bow        = $bow        ?? null;
$bowId      = $bow['id']  ?? ($_GET['id'] ?? null);
$bowName    = (is_array($bow) && isset($bow['name'])) ? $bow['name'] : ($_GET['name'] ?? null);
$pageTitle  = $pageTitle  ?? null;

// Base
$breadcrumbs = [
    ['url' => '/', 'label' => 'Accueil']
];

// ---- Archets ----
if (strpos($_SERVER['PHP_SELF'], '/arcus/') !== false) {
    $breadcrumbs[] = ['url' => '/arcus/index.php', 'label' => 'Archets'];

    // Page gamme
    if (!empty($rangeSlug)) {
        $label = $rangeName ?: ucfirst(str_replace('-', ' ', $rangeSlug));
        $breadcrumbs[] = [
            'url' => "/arcus/arcus.php?range=" . urlencode($rangeSlug),
            'label' => $label
        ];
    }

    // Page fiche archet
    if (!empty($bowId)) {
        // Si on est sur la fiche archet, on essaie de récupérer la gamme
        if (empty($rangeSlug) && !empty($bow['range_slug'])) {
            $rangeSlug = $bow['range_slug'];
            $rangeName = $bow['range_name'] ?? ucfirst(str_replace('-', ' ', $rangeSlug));
            $breadcrumbs[] = [
                'url' => "/arcus/arcus.php?range=" . urlencode($rangeSlug),
                'label' => $rangeName
            ];
        }
        $label = !empty($bowName) ? $bowName : 'Archet n°' . $bowId;
        $breadcrumbs[] = ['url' => '#', 'label' => $label];
    }
}

// ---- Articles ----
elseif (strpos($_SERVER['PHP_SELF'], '/scripta/') !== false) {
    $breadcrumbs[] = ['url' => '/scripta/index.php', 'label' => 'Articles'];

    // Page article individuelle
    if (!empty($pageTitle)) {
        $breadcrumbs[] = ['url' => '#', 'label' => htmlspecialchars($pageTitle)];
    }
}

// ---- Fallback ----
elseif (!empty($pageTitle)) {
    $breadcrumbs[] = ['url' => '#', 'label' => htmlspecialchars($pageTitle)];
}
?>

<nav class="breadcrumbs">
  <?php foreach ($breadcrumbs as $i => $b): ?>
    <?php if ($i > 0): ?> › <?php endif; ?>
    <?php if ($b['url'] !== '#'): ?>
      <a href="<?= htmlspecialchars($b['url']) ?>"><?= htmlspecialchars($b['label']) ?></a>
    <?php else: ?>
      <span><?= htmlspecialchars($b['label']) ?></span>
    <?php endif; ?>
  <?php endforeach; ?>
</nav>
