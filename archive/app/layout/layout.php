<!doctype html>
<html lang="fr">

<head>
  <?php require ROOT . '/app/metadata/head.php'; ?>
</head>

<body class="<?= e($bodyClass ?? '') ?>">

  <?php require ROOT . '/app/layout/header.php'; ?>

  <?php
  /*
   * La page demandée est rendue ici.
   * Elle ne doit contenir ni <html>, ni <head>, ni <body>, ni <main>.
   */
  ob_start();
  require $viewPath;
  $pageContent = ob_get_clean();
  ?>

  <main>
    <?= $pageContent ?>
  </main>

  <?php require ROOT . '/app/layout/footer.php'; ?>
  <?php require ROOT . '/app/metadata/scripts.php'; ?>

</body>
</html>