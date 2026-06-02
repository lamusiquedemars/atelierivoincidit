<?php
/**
 * En-tête visible du site.
 *
 * La navigation principale vient de config/site.php.
 */

$navigation = $SITE['navigation'] ?? [];

/*
 * Conversion simple des routes déclarées dans site.php
 * vers les chemins publics du site.
 */
$routePaths = [
    'home' => '/',
    'arcus' => 'arcus',
    'essai' => 'essai',
    'archetier' => 'archetier',
    'contact' => 'contact',
];
?>

<header class="site-header">
    <div class="header-bar">
        <a href="<?= e(url('/')) ?>" class="blason-link" aria-label="Retour à l'accueil">
            <img
                src="<?= e(asset('images/blason-ivo-incidit2.png')) ?>"
                alt="Ivo Incidit"
                class="blason"
            >
        </a>

        <nav class="nav-menu" aria-label="Navigation principale">
            <ul>
                <?php foreach ($navigation as $item): ?>
                    <?php
                    $label = $item['label'] ?? '';
                    $route = $item['route'] ?? '';
                    $path = $routePaths[$route] ?? $route;
                    ?>
                    <?php if ($route === 'arcus'): ?>
                        <li class="parent">
                            <a href="<?= e(url($path)) ?>">
                                <?= e($label) ?>
                            </a>
                            <ul class="submenu">
                                <li><a href="<?= e(url('arcus/ars-antiqua')) ?>">Ars Antiqua</a></li>
                                <li><a href="<?= e(url('arcus/ars-classica')) ?>">Ars Classica</a></li>
                                <li><a href="<?= e(url('arcus/ars-nova')) ?>">Ars Nova</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="<?= e(url($path)) ?>">
                                <?= e($label) ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </nav>

        <button
            class="burger"
            id="burger"
            type="button"
            aria-label="Ouvrir le menu"
            aria-expanded="false"
        >
            ☰
        </button>
    </div>
</header>