<?php
require_once dirname(__DIR__) . '/_bootstrap.php';

$pageTitle = 'Optimisation des images';

$message = null;
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_valid_csrf();

    $folder = ROOT . '/public/assets/images';

    $result = optimize_images_in_directory($folder, true);

    $message =
        $result['resized'] . ' image(s) redimensionnée(s), ' .
        $result['checked'] . ' image(s) vérifiée(s).';

    if ($result['errors'] > 0) {
        $message .= ' ' . $result['errors'] . ' erreur(s).';
    }
}

require dirname(__DIR__) . '/_header.php';
?>

<h1>Optimisation des images</h1>

<p>
    Cet outil parcourt récursivement <code>public/assets/images</code>
    et redimensionne les JPEG/PNG trop larges.
</p>

<?php if ($message): ?>
    <p><strong><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></strong></p>
<?php endif; ?>

<form method="post" class="optimize">
    <?= csrf_field() ?>

    <button type="submit">
        ⚡ Alléger les images
    </button>
</form>

<p>
    <a href="<?= htmlspecialchars(admin_url(), ENT_QUOTES, 'UTF-8') ?>">
        Retour au tableau de bord
    </a>
</p>

<?php require dirname(__DIR__) . '/_footer.php'; ?>