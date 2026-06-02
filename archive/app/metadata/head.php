<?php

/**
 * En-tête HTML commun du site.
 *
 * Ce fichier ouvre le document HTML et charge les métadonnées,
 * les polices, les feuilles de style globales et les scripts communs.
 *
 * Les pages peuvent définir avant son inclusion :
 * - $title ;
 * - $description ;
 * - $bodyClass.
 */

$pageTitle = $title ?? $GLOBALS['app']['name'];
$pageDescription = $description ?? $GLOBALS['app']['description'];
$bodyClass = $bodyClass ?? '';
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e($pageDescription) ?>">
    <meta name="author" content="Ivo Incidit">

    <title><?= e($pageTitle) ?></title>

    <!-- Connexion anticipée aux polices Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Polices -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Cormorant+SC:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS communs -->
    <link rel="stylesheet" href="<?= e(asset('css/base.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/theme.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/header.css')) ?>">
    <link rel="stylesheet" href="<?= e(asset('css/contact.css')) ?>">
    <!--fancybox-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/carousel/carousel.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/carousel/carousel.arrows.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/carousel/carousel.dots.css">

    <link rel="stylesheet" href="../css/arcus-detail.css">

    <!-- Favicon -->
    <link rel="icon" href="<?= e(asset('images/favicon.ico')) ?>" type="image/x-icon">
    <link rel="apple-touch-icon" href="<?= e(asset('images/apple-touch-icon.png')) ?>">
    <!-- Open Graph -->
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($pageDescription) ?>">
    <meta property="og:image" content="<?= e(asset('images/og-image.jpg')) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e($GLOBALS['app']['url'] ?? '') ?>">
</head>

<body class="<?= e($bodyClass) ?>">