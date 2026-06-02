<?php

$localConfigPath = __DIR__ . '/../config.local.php';
$prodConfigPath = __DIR__ . '/../config.prod.php';

if (file_exists($localConfigPath)) {
    $config = require $localConfigPath;
} elseif (file_exists($prodConfigPath)) {
    $config = require $prodConfigPath;
} else {
    die('Configuration manquante.');
}

$dbConfig = $config['db'] ?? null;

if (!$dbConfig) {
    die('Configuration base de données manquante.');
}

$host = $dbConfig['host'] ?? null;
$port = $dbConfig['port'] ?? null;
$db = $dbConfig['name'] ?? null;
$user = $dbConfig['user'] ?? null;
$pass = $dbConfig['pass'] ?? null;
$charset = $dbConfig['charset'] ?? 'utf8mb4';

if (!$host || !$db || !$user) {
    die('Configuration base de données incomplète.');
}

$dsn = "mysql:host={$host};";

if (!empty($port)) {
    $dsn .= "port={$port};";
}

$dsn .= "dbname={$db};charset={$charset}";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Connexion à la base de données impossible.');
}