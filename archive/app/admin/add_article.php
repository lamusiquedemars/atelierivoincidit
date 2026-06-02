<?php
// add_article.php
require_once __DIR__ . '/../includes/secure_functions.php';
ensure_admin();

?>
<!DOCTYPE html>
<html lang="fr">
<!-- Head du site -->
  <?php include('../includes/head.php'); ?>
  <title>Nouvel article — Admin</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/mode/xml/xml.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.5/codemirror.min.css">
  <link rel="stylesheet" href="../css/admin.css">
  <style>
    .CodeMirror { border: 1px solid #ccc; height: auto; min-height: 300px; }
    #preview-zone { border: 1px solid var(--cumaru); padding: 16px; margin-top: 20px; background: #f9f7f2; }
  </style>
</head>
<body>
<?php include('../includes/header.php'); ?>

<main class="admin-container">
  <p><a href="index.php">← Retour au tableau de bord</a></p>
  <h1>Ajouter un nouvel article</h1>
  <form id="article" action="insert_article.php" method="post">
    <label for="title">Titre</label>
    <input type="text" name="title" id="title" required>

    <label for="slug">Slug</label>
    <input type="text" name="slug" id="slug" required>

    <label for="image">Nom de l'image</label>
    <input type="text" name="image" id="image">

    <label for="style">Classe CSS personnalisée</label>
    <input type="text" name="style" id="style">

    <label for="content">Contenu HTML</label>
    <textarea name="content" id="content"></textarea>

    <button type="button" onclick="previewArticle()">Prévisualiser</button>
    <button type="submit">Enregistrer</button>
  </form>

  <h2>Aperçu</h2>
  <div id="preview-zone"></div>
</main>

<script>
  const editor = CodeMirror.fromTextArea(document.getElementById("content"), {
    mode: "xml",
    htmlMode: true,
    lineNumbers: true,
    lineWrapping: true
  });

  function previewArticle() {
    const content = editor.getValue();
    document.getElementById("preview-zone").innerHTML = content;
  }
  // Génération automatique du slug à partir du titre
document.getElementById('title').addEventListener('input', function () {
  const raw = this.value;
  const slug = raw
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[^a-z0-9 ]/g, '')
    .trim()
    .replace(/\s+/g, '-');
  document.getElementById('slug').value = slug;
});

</script>

</body>
</html>
