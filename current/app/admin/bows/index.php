<?php
/**
 * Liste des archets dans l’admin.
 */
require_once dirname(__DIR__) . '/_bootstrap.php';
require_once __DIR__ . '/_helpers.php';

$pageTitle = 'Archets';
$pdo = db();

$stmt = $pdo->query(
    'SELECT
        bow.id,
        bow.code,
        bow.name,
        bow.status,
        bow.price,
        bow.active,
        instrument.name AS instrument_name,
        bow_range.name AS range_name
    FROM bow
    LEFT JOIN instrument ON instrument.id = bow.instrument_id
    LEFT JOIN `range` AS bow_range ON bow_range.id = bow.range_id
    ORDER BY bow.code ASC'
);

$bows = $stmt->fetchAll(PDO::FETCH_ASSOC);

require dirname(__DIR__) . '/_header.php';
?>

<div class="admin-container">
    <p><a href="<?= admin_h(admin_url()) ?>">← Tableau de bord</a></p>

    <header class="admin-header-block">
        <h1>Archets</h1>
        <p><a href="<?= admin_h(admin_url('bows/create')) ?>">+ Créer un archet</a></p>
    </header>

    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Nom</th>
                <th>Instrument</th>
                <th>Gamme</th>
                <th>Prix</th>
                <th>Statut</th>
                <th>Visible</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bows as $bow): ?>
                <tr>
                    <td><?= admin_h($bow['code']) ?></td>
                    <td><?= admin_h($bow['name'] ?? '') ?></td>
                    <td><?= admin_h($bow['instrument_name'] ?? '') ?></td>
                    <td><?= admin_h($bow['range_name'] ?? '') ?></td>
                    <td><?= $bow['price'] ? admin_h(price_from_cents($bow['price'])) . ' €' : '—' ?></td>
                    <td><?= admin_h($bow['status']) ?></td>
                    <td><?= (int) $bow['active'] === 1 ? 'oui' : 'non' ?></td>
                    <td><a href="<?= admin_h(admin_url('bows/edit') . '?id=' . (int) $bow['id']) ?>">Modifier</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require dirname(__DIR__) . '/_footer.php'; ?>
