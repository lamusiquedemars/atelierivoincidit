<?php
// Connexion à la BDD
require_once '../includes/db.php';

// Préparation des données
$data = $_POST;

// Champs requis simples
$required = ['title', 'slug', 'content'];
foreach ($required as $key) {
    if (empty($data[$key])) {
        die("Le champ $key est obligatoire.");
    }
}

// Préparer l'insertion
$columns = array_keys($data);
$placeholders = array_fill(0, count($columns), '?');

$sql = "INSERT INTO article (" . implode(',', $columns) . ", date_published, enabled) VALUES (" . implode(',', $placeholders) . ", NOW(), 1)";
$stmt = $pdo->prepare($sql);
$stmt->execute(array_values($data));

// Redirection
header('Location: add_article.php');
exit;
