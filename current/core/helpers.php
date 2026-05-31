<?php
/**
 * Helpers globaux de l'application.
 *
 * Fichier unique compatible avec les deux structures existantes.
 * Il garde les mêmes noms de fonctions :
 * base_path, app_path, config_path, public_path,
 * url, asset, img, e, esc_attr, load_env,
 * dd, is_dev, render, slot_add, route.
 */


/* ============================================================
 * PATH HELPERS
 * ============================================================ */

/**
 * Chemin racine du projet.
 *
 * Priorité :
 * - constante ROOT si elle existe ;
 * - sinon dossier parent du dossier courant.
 */
function base_path(string $path = ''): string
{
    $root = defined('ROOT') ? ROOT : dirname(__DIR__);

    return rtrim($root, DIRECTORY_SEPARATOR)
        . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : '');
}

function app_path(string $path = ''): string
{
    return base_path('app' . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : ''));
}

function config_path(string $path = ''): string
{
    return base_path('config' . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : ''));
}

function public_path(string $path = ''): string
{
    return base_path('public' . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : ''));
}

/**
 * Compare une route avec paramètres simples au chemin courant.
 *
 * Exemple :
 * route : arcus/{code}
 * path  : arcus/A123
 * retour : ['code' => 'A123']
 */
function matchDynamicRoute(string $routePath, string $currentPath): ?array
{
    if (!str_contains($routePath, '{')) {
        return null;
    }

    $routeParts = explode('/', normalizePath($routePath));
    $pathParts = explode('/', normalizePath($currentPath));

    if (count($routeParts) !== count($pathParts)) {
        return null;
    }

    $params = [];

    foreach ($routeParts as $index => $routePart) {
        $pathPart = $pathParts[$index];

        if (preg_match('/^\{([a-zA-Z_][a-zA-Z0-9_]*)\}$/', $routePart, $matches)) {
            $params[$matches[1]] = rawurldecode($pathPart);
            continue;
        }

        if ($routePart !== $pathPart) {
            return null;
        }
    }

    return $params;
}

/* ============================================================
 * ESCAPE HELPERS
 * ============================================================ */

function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function esc_attr($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}


/* ============================================================
 * URL HELPERS
 * ============================================================ */

/**
 * Génère une URL à partir d'un chemin relatif.
 *
 * Compatible avec :
 * - $GLOBALS['app']['base']
 * - BASE_URL
 */
function url($path = ''): string
{
    if (is_array($path)) {
        return '';
    }

    if (isset($GLOBALS['app']['base'])) {
        $base = rtrim((string) $GLOBALS['app']['base'], '/');
    } elseif (defined('BASE_URL')) {
        $base = rtrim((string) BASE_URL, '/');
    } else {
        $base = '';
    }

    $path = trim((string) $path, '/');

    if ($base === '') {
        return $path ? '/' . $path : '/';
    }

    return $base . ($path ? '/' . $path : '');
}

/**
 * Génère une URL depuis une route nommée.
 */
function route(string $name): string
{
    global $ROUTES;

    if (!isset($ROUTES[$name])) {
        return '#';
    }

    return url($ROUTES[$name]['path'] ?? '');
}


/* ============================================================
 * ASSET HELPERS
 * ============================================================ */

/**
 * Génère une URL vers /assets.
 *
 * Exemple :
 * asset('css/style.css')
 * => /assets/css/style.css
 */
function asset(string $path = ''): string
{
    return url('assets/' . ltrim($path, '/'));
}

/**
 * Génère une URL vers /assets/images.
 *
 * Exemple :
 * img('logo.png')
 * => /assets/images/logo.png
 */
function img(string $path): string
{
    return asset('images/' . ltrim($path, '/'));
}


/* ============================================================
 * ENV HELPERS
 * ============================================================ */

/**
 * Charge un fichier .env simple.
 */
function load_env(string $path): void
{
    if (!is_file($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }

        [$key, $value] = array_pad(explode('=', $line, 2), 2, '');

        $key = trim($key);
        $value = trim($value);

        if ($key === '') {
            continue;
        }

        $_ENV[$key] = $value;
        putenv($key . '=' . $value);
    }
}

/**
 * Vérifie si l'environnement est en dev.
 *
 * Compatible avec :
 * - constante APP_ENV
 * - variable $_ENV['APP_ENV']
 * - getenv('APP_ENV')
 */
function is_dev(): bool
{
    if (defined('APP_ENV')) {
        return APP_ENV === 'dev';
    }

    if (isset($_ENV['APP_ENV'])) {
        return $_ENV['APP_ENV'] === 'dev';
    }

    return getenv('APP_ENV') === 'dev';
}


/* ============================================================
 * VIEW / COMPONENT HELPERS
 * ============================================================ */

/**
 * Charge un composant PHP et lui passe des props.
 *
 * Exemple :
 * render('hero', [
 *   'title' => 'Accueil',
 *   'class' => ['hero--page', 'hero--center']
 * ]);
 */
function render(string $block, array $props = []): void
{
    $file = app_path('components/' . basename($block) . '.php');

    if (!file_exists($file)) {
        throw new Exception('Component not found: ' . $block);
    }

    foreach ($props as $key => $value) {
        if ($key === 'class' && is_array($value)) {
            $props[$key] = implode(' ', $value);
        }
    }

    require $file;
}

/**
 * Ajoute du contenu dans un slot.
 */
function slot_add(string $type, string $value): void
{
    global $slots;

    if (!isset($slots)) {
        $slots = [];
    }

    if (!isset($slots[$type])) {
        $slots[$type] = [];
    }

    $slots[$type][] = $value;
}


/* ============================================================
 * DEBUG HELPERS
 * ============================================================ */

/**
 * Dump + stop.
 */
function dd($data): void
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    exit;
}