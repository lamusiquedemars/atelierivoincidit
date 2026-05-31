<?php
require_once __DIR__ . '/../includes/db.php';

// Récupération de l'article par slug
$slug = $_GET['slug'] ?? '';
if (!$slug) {
  http_response_code(404);
  echo "Article introuvable.";
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM article WHERE slug = ? AND enabled = 1 LIMIT 1");
$stmt->execute([$slug]);
$article = $stmt->fetch();

if (!$article) {
  http_response_code(404);
  echo "Article non trouvé.";
  exit;
}

$dateEnFrancais = (new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format(new DateTime($article['date_published']));

?>
<!DOCTYPE html>
<html lang="fr">
  <!-- Head du site -->
  <?php include('../includes/head.php'); ?>
    <title><?= htmlspecialchars($article['title']) ?> — Ivo Incidit</title>
  <link rel="stylesheet" href="../css/article.css">
  </head>
  <body>
    <?php include('../includes/header.php'); ?>
    <section class="page-hero">
      <h1><?= htmlspecialchars($article['title']) ?></h1>
      <p class="date">
        <?= $dateEnFrancais ?>
      </p>
    </section>

    <main>
       <?php include('../includes/breadcrumbs.php');?>

      <article class="article-content <?= htmlspecialchars($article['style'] ?? '') ?>">

        <?php if (!empty($article['image'])): ?>
          <figure>
            <img src="/scripta/<?= htmlspecialchars($article['image']) ?>" alt="Illustration">
          </figure>
        <?php endif; ?>

        <section class="body">
          <?= $article['content'] ?>
        </section>
      </article>
    </main>

    <?php include('../includes/footer.php'); ?>
  </body>
</html>
