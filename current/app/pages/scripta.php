<?php
require_once __DIR__ . '/../includes/db.php';

$sql = "SELECT title, slug, content, image, date_published FROM article ORDER BY date_published DESC";
$stmt = $pdo->query($sql);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
  <!-- Head du site -->
  <?php include('../includes/head.php'); ?>
  <title>Scripta — Ivo Incidit</title>
  <link rel="stylesheet" href="../css/scripta.css">
</head>
<body>

  <!-- Header du site -->
  <?php include('../includes/header.php'); ?>

    <!-- Introduction -->
    <section class="page-hero">
      <h1>Articles</h1>
      <p>
        Réflexions sur l'archèterie, la matière, le geste et l'histoire.<br>
        Notes d’atelier et éclats d’encre.
      </p>
    </section>
  <main class="scripta-container">
     <?php include('../includes/breadcrumbs.php');?>

    <!-- Navigation par tags -->
    <!--<nav class="scripta-tags">
      <ul>
        <li><a href="?tag=cumaru">cumaru</a></li>
        <li><a href="?tag=forme-de-tête">forme de tête</a></li>
        <li><a href="?tag=sustain">sustain</a></li>
        <li><a href="?tag=archets-anciens">archets anciens</a></li>
        <li><a href="?tag=artisanat-contemporain">artisanat contemporain</a></li>
      </ul>
    </nav>-->

    <!-- Grille des articles -->
    <section class="scripta-grid">
      <?php
      foreach ($articles as $article) {
          $image = !empty($article['image']) ? htmlspecialchars($article['image']) : '/assets/images/merle.png';
          $title = htmlspecialchars($article['title']);
          $slug = htmlspecialchars($article['slug']);
          $excerpt = mb_substr(strip_tags($article['content']), 0, 150) . '...';
          $date = date('d/m/Y', strtotime($article['date_published']));
          echo <<<HTML
          <article class="scripta-card">
          <img src="{$image}" alt="Illustration de l'article">
          <h2><a href="article.php?slug={$slug}">{$title}</a></h2>
          <p class="date">{$date}</p>
          <p class="resume">{$excerpt}</p>
          <a href="article.php?slug={$slug}" class="read-more">{Lire l'article →</a>
          </article>
      HTML;
      }
      ?>
    </section>

    <!-- Pagination -->
    <div class="scripta-pagination">
      <a href="#" class="pagination-button">Articles suivants →</a>
    </div>

  </main>

  <?php include('../includes/footer.php'); ?>
</body>
</html>
