<?php
require_once dirname(__DIR__, 2) . '/core/bootstrap.php';

if (current_admin() !== null) {
  header('Location: ' . admin_url());
  exit;
}

$pageTitle = 'Connexion admin';

$next = $_GET['next'] ?? admin_url();
$csrf = csrf_token();
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?> — Ivo Incidit</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body class="admin-login">
  <main class="admin-login-card" aria-labelledby="admin-login-title">
    <h1 id="admin-login-title">Connexion administrateur</h1>

    <form method="post" action="<?= htmlspecialchars(admin_url('authenticate'), ENT_QUOTES, 'UTF-8') ?>" autocomplete="off">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="next" value="<?= htmlspecialchars($next, ENT_QUOTES, 'UTF-8') ?>">

      <div class="admin-field">
        <label for="username">Nom d’utilisateur</label>
        <input id="username" name="username" type="text" required autofocus>
      </div>

      <div class="admin-field">
        <label for="password">Mot de passe</label>
        <input id="password" name="password" type="password" required>
      </div>

      <button type="submit">Se connecter</button>
    </form>
  </main>
</body>

</html>