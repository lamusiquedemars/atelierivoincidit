<?php
/**
 * Configuration de la base de données.
 *
 * Ce fichier lit les paramètres de connexion depuis les variables
 * d'environnement chargées au démarrage de l'application.
 *
 * Les identifiants réels doivent rester dans .env.
 */

return [
    'host' => $_ENV['DB_HOST'] ?? '',
    'port' => $_ENV['DB_PORT'] ?? '',
    'name' => $_ENV['DB_NAME'] ?? '',
    'user' => $_ENV['DB_USER'] ?? '',
    'pass' => $_ENV['DB_PASS'] ?? '',
    'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
];