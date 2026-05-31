<?php
/**
 * Formulaire de modification d’un archet existant.
 */
require_once dirname(__DIR__) . '/_bootstrap.php';
require_once __DIR__ . '/_helpers.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: ' . admin_url('bows'));
    exit;
}

$pageTitle = 'Modifier un archet';
$pdo = db();
$options = load_bow_form_options($pdo);
$bow = load_bow($pdo, $id);
$action = admin_url('bows/update');
$submitLabel = 'Enregistrer les modifications';

require dirname(__DIR__) . '/_header.php';
?>

<div class="admin-container">
    <p><a href="<?= admin_h(admin_url('bows')) ?>">← Archets</a></p>
    <h1>Modifier <?= admin_h($bow['code'] ?? 'cet archet') ?></h1>

    <?php require __DIR__ . '/_form.php'; ?>
</div>

<?php require dirname(__DIR__) . '/_footer.php'; ?>
