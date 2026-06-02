<?php

/**
 * Header admin.
 *
 * Variables attendues :
 * - $pageTitle
 */

$admin = current_admin();
$title = $pageTitle ?? 'Administration';
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?> — Ivo Incidit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body class="admin-body">
    <header class="admin-header">
        <div class="admin-header__brand">
            <a href="<?= htmlspecialchars(admin_url(), ENT_QUOTES, 'UTF-8') ?>">
                Administration
            </a>
        </div>

        <nav class="admin-nav" aria-label="Navigation admin">
            <a href="<?= htmlspecialchars(admin_url(), ENT_QUOTES, 'UTF-8') ?>">Tableau de bord</a>
            <a href="<?= htmlspecialchars(admin_url('bows'), ENT_QUOTES, 'UTF-8') ?>">Archets</a>
            <a href="<?= htmlspecialchars(admin_url('articles'), ENT_QUOTES, 'UTF-8') ?>">Articles</a>
            <a href="<?= htmlspecialchars(admin_url('tools/images'), ENT_QUOTES, 'UTF-8') ?>">Images</a>
            <a href="<?= htmlspecialchars(admin_url('logout'), ENT_QUOTES, 'UTF-8') ?>">Déconnexion</a>
        </nav>
    </header>

    <main class="admin-main">