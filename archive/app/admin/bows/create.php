<?php
/**
 * Formulaire de création d’un archet.
 */
require_once dirname(__DIR__) . '/_bootstrap.php';
require_once __DIR__ . '/_helpers.php';

$pageTitle = 'Créer un archet';
$pdo = db();
$options = load_bow_form_options($pdo);
$bow = empty_bow();
$action = admin_url('bows/store');
$submitLabel = 'Créer l’archet';

require dirname(__DIR__) . '/_header.php';
?>

<div class="admin-container">
    <p><a href="<?= admin_h(admin_url('bows')) ?>">← Archets</a></p>
    <h1>Créer un archet</h1>

    <?php require __DIR__ . '/_form.php'; ?>
</div>

<?php require dirname(__DIR__) . '/_footer.php'; ?>
