<?php
/**
 * Configuration principale du site.
 */

return [
    // Identité
    'name' => 'Atelier Ivo Incidit',
    'author' => 'Ivo Correia de Melo',

    // Environnement
    'env' => $_ENV['APP_ENV'] ?? 'prod',
    'url' => $_ENV['APP_URL'] ?? 'http://atelierivoincidit.local',
    'base' => $_ENV['APP_BASE'] ?? '',

    // SEO
    'description' => 'Archets artisanaux faits main par Ivo Correia de Melo.',
    'seo' => [
        'default_title' => 'Atelier Ivo Incidit',
        'default_description' => 'Archets artisanaux faits main par Ivo Correia de Melo.',
    ],

    // Assets
    'assets' => [
        'logo' => 'images/logo.png',
        'default_image' => 'images/og-image.jpg',
        'favicon_path' => 'images/favicon/',
    ],

    // Navigation
    'navigation' => [
        ['label' => 'Archets', 'route' => 'arcus'],
        ['label' => 'Essai', 'route' => 'essai'],
        ['label' => 'Archetier', 'route' => 'archetier'],
        ['label' => 'Contact', 'route' => 'contact'],
    ],

    // Footer
    'footer' => [
        'baseline' => 'Archets artisanaux fabriqués à Lyon.',
        'links' => [
            ['label' => 'Mentions légales', 'route' => 'mentions-legales'],
            ['label' => 'Contact', 'route' => 'contact'],
        ],
        'socials' => [
            ['label' => 'Instagram', 'url' => 'https://www.instagram.com/ivo_incidit'],
        ],
    ],

    // Réalisations / projets
    'realisations' => [
        'title' => '',
        'items' => [],
    ],

    // Tracking
    'tracking' => [
        'ga_id' => null,
    ],
];