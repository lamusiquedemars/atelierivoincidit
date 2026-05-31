

<?php
/**ancienne page arcus-range.php
 * Page d'une gamme d'archets.
 *
 * Cette page sert de modèle commun pour les gammes Ars Antiqua,
 * Ars Classica et Ars Nova.
 */
//require_once __DIR__ . '/../includes/db.php';

// Récupération des filtres
$rangeSlug = $_GET['range'] ?? null;
$instrumentFilter = trim($_GET['instrument'] ?? '');

//remplir le titre et description avec le nom de la gamme 
//Si une gamme est demandée
$rangeData = null;
if (!empty($rangeSlug)) {
    $stmtRange = $pdo->prepare("SELECT name, description FROM `range` WHERE slug = :slug LIMIT 1");
    $stmtRange->execute(['slug' => $rangeSlug]);
    $rangeData = $stmtRange->fetch(PDO::FETCH_ASSOC);
}
// Si on a des infos, on remplace le titre et le texte
$heroTitle = $rangeData['name'] ?? 'Arcus';
$heroText  = $rangeData['description'] ?? "La collection d'archets façonnés à la main";

// Requête SQL complète
$sql = "
    SELECT 
        b.id,
        b.code,
        b.name AS atelier_name,
        b.status,
        b.price,
        b.discount,
        i.name AS instrument_name,
        s.name AS style_name,
        sh.name AS shape_name,
        w.name AS wood_name,
        o.name AS origin_name,
        c.name AS color_name,
        fm.name AS frog_material_name,
        sm.name AS slide_material_name,
        bm.name AS button_material_name,
        tm.name AS tip_material_name,
        g.name AS garnish_name,
        sz.name AS size_name,
        `r`.name AS range_name,
        `r`.slug AS range_slug,
        b.short_trait,
        q1.name AS flexibility_name,
        q2.name AS responsiveness_name,
        q3.name AS handling_name,
        q4.name AS natural_pressure_name,
        q5.name AS tone_name,
        q6.name AS projection_name,
        q7.name AS sustain_name,
        q8.name AS articulation_name
    FROM bow b
    LEFT JOIN instrument i ON b.instrument_id = i.id
    LEFT JOIN style s ON b.style_id = s.id
    LEFT JOIN shape sh ON b.shape_id = sh.id
    LEFT JOIN wood w ON b.wood_id = w.id
    LEFT JOIN origin o ON b.origin_id = o.id
    LEFT JOIN color c ON b.color_id = c.id
    LEFT JOIN material fm ON b.frog_material_id = fm.id
    LEFT JOIN material sm ON b.slide_material_id = sm.id
    LEFT JOIN material bm ON b.button_material_id = bm.id
    LEFT JOIN material tm ON b.tip_material_id = tm.id
    LEFT JOIN garnish g ON b.garnish_id = g.id
    LEFT JOIN size sz ON b.size_id = sz.id
    LEFT JOIN `range` r ON b.range_id = r.id
    LEFT JOIN quality q1 ON b.flexibility_id = q1.id
    LEFT JOIN quality q2 ON b.responsiveness_id = q2.id
    LEFT JOIN quality q3 ON b.handling_id = q3.id
    LEFT JOIN quality q4 ON b.natural_pressure_id = q4.id
    LEFT JOIN quality q5 ON b.tone_id = q5.id
    LEFT JOIN quality q6 ON b.projection_id = q6.id
    LEFT JOIN quality q7 ON b.sustain_id = q7.id
    LEFT JOIN quality q8 ON b.articulation_id = q8.id
";


// Toujours filtrer les archets actifs
$conditions[] = 'b.active = 1';

// Construction dynamique du WHERE
$conditions = [];
$params = [];

// Filtrage par gamme
if (!empty($rangeSlug)) {
    $conditions[] = 'r.slug = :slug';
    $params['slug'] = $rangeSlug;
}
// Filtrage par instrument
if (!empty($instrumentFilter)) {
    $conditions[] = 'i.name = :instrument';
    $params['instrument'] = $instrumentFilter;
}
// Filtrage par active
$conditions[] = 'b.active = 1';

if (!empty($conditions)) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}

$sql .= ' ORDER BY b.code ASC';

// Exécution de la requête
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$bows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Préparer les chemins d'images
foreach ($bows as &$b) {
    $code = strtolower($b['code']);
    $photo_dir = __DIR__ . "/images/$code/";
    $web_photo_dir = "images/$code/";
    $thumb = '';

    if (is_dir($photo_dir)) {
        $main = glob($photo_dir . "main.{jpg,jpeg,png,heic}", GLOB_BRACE);
        if (!empty($main)) {
            $thumb = $web_photo_dir . basename($main[0]);
        } else {
            $all_photos = glob($photo_dir . "*.{jpg,jpeg,png,heic}", GLOB_BRACE);
            if (!empty($all_photos)) {
                $thumb = $web_photo_dir . basename($all_photos[0]);
            }
        }
    }
    $b['thumb'] = $thumb;
}
unset($b);
?>
<!DOCTYPE html>
<html lang="fr">
<!-- Head du site -->
<?php include('../includes/head.php'); ?>
<title>Arcus – Ivo Incidit</title>
<link rel="stylesheet" href="../css/arcus-list.css">
</head>

<body>
    <?php include('../includes/header.php'); ?>

    <section class="page-hero">
        <h1><?= htmlspecialchars($heroTitle) ?></h1>
        <p><?= nl2br(htmlspecialchars($heroText)) ?></p>
    </section>
    <main>
        <?php include('../includes/breadcrumbs.php'); ?>
        <section class="arcus-intro">
            <p>
                Voici un aperçu des archets de la gamme <?= htmlspecialchars($heroTitle) ?>.<br>
                Si vous ne trouvez pas exactement ce que vous cherchez, je peux également fabriquer un archet sur demande, selon vos besoins et vos préférences.
            </p>
            <div class="cta">
                <a class="button" href="/contact.php">Me contacter pour une commande personnalisée</a>
            </div>
        </section>
        <div class="arcus-controls">
            <form method="get" action="">
                <?php if ($rangeSlug): ?>
                    <input type="hidden" name="range" value="<?= htmlspecialchars($rangeSlug) ?>">
                <?php endif; ?>
                <label for="instrument">Instrument :</label>
                <select name="instrument" id="instrument" onchange="this.form.submit()">
                    <option value="">Tous</option>
                    <?php
                    $instRes = $pdo->query("SELECT name FROM instrument ORDER BY name");
                    while ($row = $instRes->fetch(PDO::FETCH_ASSOC)) {
                        $sel = ($row['name'] === $instrumentFilter) ? ' selected' : '';
                        echo "<option value=\"{$row['name']}\"$sel>{$row['name']}</option>";
                    }
                    ?>
                </select>
            </form>
        </div>

        <section class="arcus-list">
            <?php if ($bows): ?>
                <?php foreach ($bows as $b): ?>
                    <?php
                    // --- Calcul du prix pour cet archet ---
                    $price        = isset($b['price']) ? (int)$b['price'] : null;   // en centimes
                    $discount     = isset($b['discount']) ? (int)$b['discount'] : 0;
                    $finalPrice   = null;
                    $hasDiscount  = false;
                    // Afficher seulement si pas vendu et prix présent
                    if ($b['status'] !== 'sold' && $price !== null) {
                        if ($discount > 0) {
                            $hasDiscount = true;
                            $finalPrice = $price - (int) round(($price * $discount) / 100);
                        } else {
                            $finalPrice = $price;
                        }
                    }
                    ?>
                    <article class="bloc-section">
                        <div class="col">
                            <?php if (!empty($b['thumb'])): ?>
                                <img src="/arcus/<?= htmlspecialchars($b['thumb']) ?>"
                                    alt="Archet <?= htmlspecialchars($b['range_name'] . ' ' . $b['instrument_name']) ?>"
                                    loading="lazy">
                            <?php else: ?>
                                <div class="no-thumb">Pas d'image disponible</div>
                            <?php endif; ?>
                        </div>
                        <div class="col">
                            <h2>
                                <?= htmlspecialchars($b['range_name']) ?> n°<?= htmlspecialchars($b['id']) ?>
                                <?php if (!empty($b['atelier_name'])): ?>
                                    <span class="atelier-name">“<?= htmlspecialchars($b['atelier_name']) ?>”</span>
                                <?php endif; ?>
                                – <?= htmlspecialchars($b['instrument_name']) ?> <?= htmlspecialchars($b['size_name']) ?>
                                <?php if ($finalPrice !== null): ?>
                                    –
                                    <?php if ($hasDiscount): ?>
                                        <span class="price-new">
                                            <?= number_format($finalPrice / 100, 2, ',', ' ') ?> €
                                        </span>
                                        <span class="price-old">
                                            <?= number_format($price / 100, 2, ',', ' ') ?> €
                                        </span>
                                    <?php else: ?>
                                        <span class="price-std">
                                            <?= number_format($finalPrice / 100, 2, ',', ' ') ?> €
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if ($b['status'] === 'sold'): ?>
                                    <span class="badge sold">Vendu</span>
                                <?php elseif ($b['status'] === 'unavailable'): ?>
                                    <span class="badge unavailable">Indisponible</span>
                                <?php endif; ?>
                            </h2>

                            <p>
                                Bois : <?= htmlspecialchars($b['wood_name']) ?>
                                – Couleur : <?= htmlspecialchars($b['color_name']) ?><br>
                                Style : <?= htmlspecialchars($b['style_name']) ?> – Forme : <?= htmlspecialchars($b['shape_name']) ?><br>
                                <?= !empty($b['short_trait']) ? htmlspecialchars($b['short_trait']) : '' ?>
                            </p>

                            <div class="cta">
                                <a class="button" href="fiche.php?code=<?= htmlspecialchars($b['code']) ?>">
                                    Voir la fiche complète
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucun archet trouvé.</p>
            <?php endif; ?>








        </section>
    </main>
    <?php include('../includes/footer.php'); ?>
</body>

</html>