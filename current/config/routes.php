<?php

/**
 * Routes publiques du site Atelier Ivo Incidit.
 *
 * Convention réutilisable :
 * - la clé principale est le nom logique de la route ;
 * - path est l’URL publique, sans slash initial ;
 * - view est le nom de la vue dans app/pages/, sans .php ;
 * - params contient les paramètres transmis à la page.
 */

return [
    'home' => [
        'path' => '',
        'view' => 'home',
        'title' => 'Atelier Ivo Incidit',
        'description' => 'Archets artisanaux faits main par Ivo Correia de Melo.',
        'bodyClass' => 'page-home',
    ],

    'arcus' => [
        'path' => 'arcus',
        'view' => 'arcus',
        'title' => 'Archets',
        'description' => 'Archets artisanaux faits main par Ivo Correia de Melo : Ars Antiqua, Ars Classica et Ars Nova.',
        'bodyClass' => 'page-arcus',
    ],
    'arcus-detail' => [
        'path' => 'arcus/{code}',
        'view' => 'arcus-detail',
        'title' => 'Fiche archet',
        'description' => 'Fiche détaillée d’un archet Ivo Incidit.',
        'bodyClass' => 'page-arcus-detail',
    ],
    'arcus-ars-antiqua' => [
        'path' => 'arcus/ars-antiqua',
        'view' => 'arcus-range',
        'title' => 'Ars Antiqua',
        'description' => 'Archets inspirés par les gestes anciens, le jeu baroque et les équilibres historiques.',
        'bodyClass' => 'page-arcus-range page-arcus-range-ars-antiqua',
        'params' => [
            'range' => 'ars-antiqua',
        ],
    ],

    'arcus-ars-classica' => [
        'path' => 'arcus/ars-classica',
        'view' => 'arcus-range',
        'title' => 'Ars Classica',
        'description' => 'Archets pensés pour une pratique moderne régulière, stable et exigeante.',
        'bodyClass' => 'page-arcus-range page-arcus-range-ars-classica',
        'params' => [
            'range' => 'ars-classica',
        ],
    ],

    'arcus-ars-nova' => [
        'path' => 'arcus/ars-nova',
        'view' => 'arcus-range',
        'title' => 'Ars Nova',
        'description' => 'Archets personnels, singuliers, construits autour d’une recherche de matière et de caractère.',
        'bodyClass' => 'page-arcus-range page-arcus-range-ars-nova',
        'params' => [
            'range' => 'ars-nova',
        ],
    ],

    'essai' => [
        'path' => 'essai',
        'view' => 'probatio',
        'title' => 'Essayer un archet',
        'description' => 'Informations pratiques pour essayer un archet Ivo Incidit.',
        'bodyClass' => 'page-probatio',
    ],

    'archetier' => [
        'path' => 'archetier',
        'view' => 'officina',
        'title' => 'L’archetier',
        'description' => 'Ivo Correia de Melo, archetier à Lyon, fabrique des archets contemporains
                         en bois brésiliens alternatifs, dans une démarche durable et artisanale.',
        'bodyClass' => 'page-officina',
    ],

    'contact' => [
        'path' => 'contact',
        'view' => 'contact',
        'title' => 'Contact',
        'description' => 'Contacter l’atelier Ivo Incidit pour un conseil, un essai ou une demande d’archet.',
        'bodyClass' => 'page-contact',
    ],

    'mentions-legales' => [
        'path' => 'mentions-legales',
        'view' => 'mentions-legales',
        'title' => 'Mentions légales',
        'description' => 'Mentions légales du site Atelier Ivo Incidit.',
        'bodyClass' => 'page-legal',
    ],

    'cgv' => [
        'path' => 'cgv',
        'view' => 'cgv',
        'title' => 'Conditions générales de vente',
        'description' => 'Conditions générales de vente de l’atelier Ivo Incidit.',
        'bodyClass' => 'page-cgv',
    ],

    /*
     * Administration.
     */
    'admin-dashboard' => [
        'path' => 'admin',
        'area' => 'admin',
        'view' => 'index',
        'title' => 'Administration',
        'description' => '',
        'bodyClass' => 'admin-body',
        'standalone' => true,
    ],

    'admin-login' => [
        'path' => 'admin/login',
        'area' => 'admin',
        'view' => 'login',
        'title' => 'Connexion admin',
        'description' => '',
        'bodyClass' => 'admin-login',
        'standalone' => true,
    ],

    'admin-authenticate' => [
        'path' => 'admin/authenticate',
        'area' => 'admin',
        'view' => 'authenticate',
        'title' => 'Authentification admin',
        'description' => '',
        'bodyClass' => 'admin-login',
        'standalone' => true,
    ],

    'admin-logout' => [
        'path' => 'admin/logout',
        'area' => 'admin',
        'view' => 'logout',
        'title' => 'Déconnexion',
        'description' => '',
        'bodyClass' => 'admin-login',
        'standalone' => true,
    ],

    'admin-tools-images' => [
        'path' => 'admin/tools/images',
        'area' => 'admin',
        'view' => 'tools/images',
        'title' => 'Optimisation des images',
        'description' => '',
        'bodyClass' => 'admin-body',
        'standalone' => true,
    ],
    'admin-bows' => [
        'path' => 'admin/bows',
        'area' => 'admin',
        'view' => 'bows/index',
        'title' => 'Archets',
        'description' => '',
        'bodyClass' => 'admin-body',
        'standalone' => true,
    ],

    'admin-bows-create' => [
        'path' => 'admin/bows/create',
        'area' => 'admin',
        'view' => 'bows/create',
        'title' => 'Créer un archet',
        'description' => '',
        'bodyClass' => 'admin-body',
        'standalone' => true,
    ],

    'admin-bows-store' => [
        'path' => 'admin/bows/store',
        'area' => 'admin',
        'view' => 'bows/store',
        'title' => 'Enregistrer un archet',
        'description' => '',
        'bodyClass' => 'admin-body',
        'standalone' => true,
    ],

    'admin-bows-edit' => [
        'path' => 'admin/bows/edit',
        'area' => 'admin',
        'view' => 'bows/edit',
        'title' => 'Modifier un archet',
        'description' => '',
        'bodyClass' => 'admin-body',
        'standalone' => true,
    ],

    'admin-bows-update' => [
        'path' => 'admin/bows/update',
        'area' => 'admin',
        'view' => 'bows/update',
        'title' => 'Mettre à jour un archet',
        'description' => '',
        'bodyClass' => 'admin-body',
        'standalone' => true,
    ],
    '404' => [
        'path' => '404',
        'view' => '404',
        'title' => 'Page introuvable',
        'description' => 'La page demandée est introuvable.',
        'bodyClass' => 'page-404',
    ],
];
