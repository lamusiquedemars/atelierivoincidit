<?php
require_once __DIR__ . '/_bootstrap.php';

$pageTitle = 'Tableau de bord';

$admin = current_admin();

require __DIR__ . '/_header.php';
?>

<h1>Tableau de bord</h1>

<p>
  Bienvenue
  <?= htmlspecialchars($admin['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>.
</p>

<section class="admin-grid">
  <article class="admin-card">
    <h2>Archets</h2>
    <p>Ajouter, modifier ou consulter les archets.</p>
    <a href="<?= htmlspecialchars(admin_url('bows/'), ENT_QUOTES, 'UTF-8') ?>">Gérer les archets</a>
  </article>

  <article class="admin-card">
    <h2>Articles</h2>
    <p>Créer ou modifier les contenus éditoriaux.</p>
    <a href="<?= htmlspecialchars(admin_url('articles/'), ENT_QUOTES, 'UTF-8') ?>">Gérer les articles</a>
  </article>

  <article class="admin-card">
    <h2>Outils</h2>
    <p>Accéder aux outils techniques du site.</p>
    <a href="<?= htmlspecialchars(admin_url('tools/images'), ENT_QUOTES, 'UTF-8') ?>">Optimiser les images</a>
  </article>

  <article class="admin-card">
    <h2>Données</h2>
    <p>Consulter les vitesses et les essences de bois.</p>
    <a href="<?= htmlspecialchars(admin_url('speeds'), ENT_QUOTES, 'UTF-8') ?>">Vitesses</a>
    <br>
    <a href="<?= htmlspecialchars(admin_url('woods'), ENT_QUOTES, 'UTF-8') ?>">Bois</a>
  </article>
</section>

<?php require __DIR__ . '/_footer.php'; ?>