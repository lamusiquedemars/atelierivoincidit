<?php require_once __DIR__ . '/../includes/secure_functions.php';
ensure_admin(); ?>
<!DOCTYPE html>
<html lang="fr">
  <!-- Head du site -->
  <?php include('../includes/head.php'); ?>
  <title>Ivo Incidit</title>
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

  <!-- Header du site -->
  <?php include('../includes/header.php'); ?>
<p><a href="index.php">← Retour au tableau de bord</a></p>
  <main class="fiche-carte">
    <section class="bloc">
      <img src="../assets/images/paubrasilia-echinata.jpg"/>
      <legend>pernambouc</legend>
    </section>
    <section class="bloc">
      <img src="../assets/images/dipteryx-odorata.jpg"/>
      <legend>cumaru</legend>
    </section>
    <section class="bloc">
      <img src="../assets/images/handroanthus-serratifolius.jpg" >
      <legend>ipé</legend>
    </section>
    <section class="bloc">
      <img src="../assets/images/handroanthus-heterotricha.jpg"/>
      <legend>ipé jaune</legend>
    </section>

  </main>
  <!-- footer du site -->
  <?php include('../includes/footer.php'); ?>

</body>
</html>
