<?php
/**
 * Enregistrement d’un nouvel archet.
 */
require_once dirname(__DIR__) . '/_bootstrap.php';
require_once __DIR__ . '/_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . admin_url('bows/create'));
    exit;
}

require_valid_csrf();

$pdo = db();
$payload = bow_payload_from_post($_POST);
$errors = validate_bow_payload($payload);

if ($errors !== []) {
    throw new RuntimeException(implode(' ', $errors));
}

$id = insert_bow($pdo, $payload);

header('Location: ' . admin_url('bows/edit') . '?id=' . $id);
exit;
