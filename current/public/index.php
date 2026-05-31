<?php
declare(strict_types=1);

/**
 * Point d'entrée public de l'application.
 *
 * Toutes les requêtes arrivent ici.
 * Ce fichier :
 * - charge le bootstrap ;
 * - résout la route courante ;
 * - prépare les variables communes ;
 * - délègue l'affichage au layout principal.
 */

require_once dirname(__DIR__) . '/core/bootstrap.php';
require_once ROOT . '/core/router.php';

/*
 * Résolution de la route courante.
 */
$route = resolveRoute(currentPath(), $ROUTES);

/*
 * Code HTTP retourné par le router.
 */
http_response_code($route['status'] ?? 200);

/*
 * Variables communes disponibles dans le layout et les pages.
 */
$title = $route['title'] ?? ($SITE['name'] ?? 'Site');
$description = $route['description'] ?? ($SITE['seo']['description'] ?? '');
$bodyClass = $route['bodyClass'] ?? '';

/*
 * Vue à afficher.
 */
$view = $route['view'] ?? '404';
$viewPath = $route['viewPath'] ?? ROOT . '/app/pages/404.php';

/*
 * Paramètres transmis à la page.
 *
 * Exemple :
 * routes.php :
 * 'params' => ['range' => 'ars-antiqua']
 *
 * page :
 * $range = $params['range'] ?? null;
 */
$params = $route['params'] ?? [];

/*
 * Slots facultatifs pour enrichir certaines pages.
 */
$slots = [
    'meta' => [],
    'css' => [],
    'js' => [],
    'head' => [],
];

/*
 * Affichage.
 */
/*
 * Certaines routes ont leur propre rendu complet.
 * Exemple : admin, login admin, actions POST.
 */
if (!empty($route['standalone'])) {
    require $viewPath;
    exit;
}
require ROOT . '/app/layout/layout.php';