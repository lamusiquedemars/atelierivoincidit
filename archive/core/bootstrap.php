<?php
/**
 * Bootstrap commun de l'application.
 *
 * Initialise :
 * - ROOT ;
 * - les variables d'environnement ;
 * - la configuration du site ;
 * - les routes ;
 * - les helpers ;
 * - le routeur ;
 * - le mode debug.
 */

if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

/*
 * Charge le fichier .env.
 */
function loadEnvFile(string $path): void
{
    if (!file_exists($path)) {
        return;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);

        if ($line === '' || $line[0] === '#') {
            continue;
        }

        if (!str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);

        $_ENV[trim($key)] = trim($value);
    }
}

loadEnvFile(ROOT . '/.env');

/*
 * Variables d'environnement principales.
 */
$APP_ENV  = $_ENV['APP_ENV'] ?? 'prod';
$APP_URL  = $_ENV['APP_URL'] ?? '';
$APP_BASE = $_ENV['APP_BASE'] ?? '';

$BASE_URL = rtrim($APP_URL, '/');

/*
 * Constantes utiles aux helpers.
 */
if (!defined('APP_ENV')) {
    define('APP_ENV', $APP_ENV);
}

if (!defined('BASE_URL')) {
    define('BASE_URL', $BASE_URL);
}

/*
 * Configuration du site.
 */
$SITE = require ROOT . '/config/site.php';

/*
 * Routes.
 */
$ROUTES = require ROOT . '/config/routes.php';

/*
 * Core.
 */
require_once ROOT . '/core/helpers.php';
require_once ROOT . '/core/router.php';

/*
 * Base de données éventuelle.
 * Le fichier peut être vide si le site n'utilise pas SQL.
 */
if (file_exists(ROOT . '/core/database.php')) {
    require_once ROOT . '/core/database.php';
}

/*
 * Authentification / sécurité.
 */
if (file_exists(ROOT . '/core/auth.php')) {
    require_once ROOT . '/core/auth.php';
}

/*
 * Images.
 */
if (file_exists(ROOT . '/core/images.php')) {
    require_once ROOT . '/core/images.php';
}
/*
 * Globals dérivés.
 */
$SEO = $SITE['seo'] ?? [];
$ASSETS = $SITE['assets'] ?? [];

$SITE['env'] = $APP_ENV;
$SITE['url'] = $BASE_URL;

/*
 * Compatibilité temporaire avec l'ancien Atelier Ivo Incidit.
 * À supprimer quand tout utilise $SITE.
 */
$app = $SITE;
$GLOBALS['app'] = $app;
$GLOBALS['SITE'] = $SITE;

/*
 * Debug.
 */
$isDev = ($APP_ENV === 'dev');

error_reporting($isDev ? E_ALL : 0);
ini_set('display_errors', $isDev ? '1' : '0');