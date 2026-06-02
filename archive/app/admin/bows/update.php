<?php
/**
 * Mise à jour d’un archet existant.
 */
require_once dirname(__DIR__) . '/_bootstrap.php';
require_once __DIR__ . '/_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . admin_url('bows'));
    exit;
}

require_valid_csrf();

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id <= 0) {
    header('Location: ' . admin_url('bows'));
    exit;
}

$pdo = db();
$payload = bow_payload_from_post($_POST);
$errors = validate_bow_payload($payload);

if ($errors !== []) {
    throw new RuntimeException(implode(' ', $errors));
}

update_bow($pdo, $id, $payload);

header('Location: ' . admin_url('bows/edit') . '?id=' . $id);
exit;
