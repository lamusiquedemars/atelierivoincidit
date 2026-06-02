<?php
/**
 * Connexion à la base de données.
 *
 * Ce fichier crée une connexion PDO à partir de la configuration
 * définie dans config/database.php.
 *
 * Les requêtes métier ne doivent pas être écrites ici.
 */

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $config = require config_path('database.php');

    $host = $config['host'] ?? '';
    $port = $config['port'] ?? '';
    $name = $config['name'] ?? '';
    $user = $config['user'] ?? '';
    $pass = $config['pass'] ?? '';
    $charset = $config['charset'] ?? 'utf8mb4';

    if ($host === '' || $name === '' || $user === '') {
        throw new RuntimeException('Configuration base de données incomplète.');
    }

    $dsn = "mysql:host={$host};";

    if ($port !== '') {
        $dsn .= "port={$port};";
    }

    $dsn .= "dbname={$name};charset={$charset}";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $user, $pass, $options);

    return $pdo;
}