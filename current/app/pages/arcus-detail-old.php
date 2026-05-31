<?php
require_once '../includes/db.php';

$code = isset($_GET['code']) ? $_GET['code'] : '';

// Nouvelle requête adaptée à ta base

$stmt = $pdo->prepare("SELECT b.*, 
    instrument.name AS instrument_name, 
    style.name AS style_name, 
    shape.name AS shape_name, 
    wood.name AS wood_name, 
    color.name AS color_name, 
    material_button.name AS button_material_name, 
    material_frog.name AS frog_material_name, 
    material_slide.name AS slide_material_name, 
    material_tip.name AS tip_material_name, 
    size.name AS size_name, 
    origin.name AS origin_name, 
    garnish.name AS garnish_name,
    range.name AS range_name,
    range.slug AS range_slug,
    q1.name AS flexibility_name,
    q2.name AS responsiveness_name,
    q3.name AS handling_name,
    q4.name AS natural_pressure_name,
    q5.name AS tone_name,
    q6.name AS projection_name,
    q7.name AS sustain_name,
    q8.name AS articulation_name
FROM bow b
LEFT JOIN instrument ON b.instrument_id = instrument.id
LEFT JOIN style ON b.style_id = style.id
LEFT JOIN shape ON b.shape_id = shape.id
LEFT JOIN wood ON b.wood_id = wood.id
LEFT JOIN color ON b.color_id = color.id
LEFT JOIN material AS material_button ON b.button_material_id = material_button.id
LEFT JOIN material AS material_frog ON b.frog_material_id = material_frog.id
LEFT JOIN material AS material_slide ON b.slide_material_id = material_slide.id
LEFT JOIN material AS material_tip ON b.tip_material_id = material_tip.id
LEFT JOIN `size` ON b.size_id = `size`.id
LEFT JOIN origin ON b.origin_id = origin.id
LEFT JOIN garnish ON b.garnish_id = garnish.id
LEFT JOIN `range` ON b.range_id = `range`.id
LEFT JOIN quality AS q1 ON b.flexibility_id = q1.id
LEFT JOIN quality AS q2 ON b.responsiveness_id = q2.id
LEFT JOIN quality AS q3 ON b.handling_id = q3.id
LEFT JOIN quality AS q4 ON b.natural_pressure_id = q4.id
LEFT JOIN quality AS q5 ON b.tone_id = q5.id
LEFT JOIN quality AS q6 ON b.projection_id = q6.id
LEFT JOIN quality AS q7 ON b.sustain_id = q7.id
LEFT JOIN quality AS q8 ON b.articulation_id = q8.id
WHERE b.code = ?");
$stmt->execute([$code]);
$bow = $stmt->fetch(PDO::FETCH_ASSOC);

// ----- Calcul du prix -----
$price       = isset($bow['price']) ? (int)$bow['price'] : null;   // en centimes
$discount    = isset($bow['discount']) ? (int)$bow['discount'] : 0;
$finalPrice  = null;
$hasDiscount = false;

if ($bow['status'] !== 'sold' && $price !== null) {
    if ($discount > 0) {
        $hasDiscount = true;
        $finalPrice  = $price - (int) round(($price * $discount) / 100);
    } else {
        $finalPrice = $price;
    }
}

if (!$bow) {
    echo "<p>❌ Archet introuvable.</p>";
    exit;
}

$photos = [];
$photo_dir = __DIR__ . "/images/" . strtolower($bow['code']) . "/";
$web_photo_dir = "images/" . strtolower($bow['code']) . "/";
if (is_dir($photo_dir)) {
    $photos = glob($photo_dir . "*.{jpg,jpeg,png,heic}", GLOB_BRACE);
}
?>
<!DOCTYPE html>
<html lang="fr">
  <!-- Head du site -->
  <?php include('../includes/head.php'); ?>
  <title>Fiche <?= htmlspecialchars($bow['code']) ?> – Ivo Incidit</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"/>
  <link rel="stylesheet" href="../css/arcus-detail.css">
  <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
</head>
<body>
<?php include('../includes/header.php'); ?>

<section class="page-hero">
  <div class="hero-content">
    <!--<h1>Archet <?= htmlspecialchars($bow['style_name']) ?> pour <?= htmlspecialchars($bow['instrument_name']) ?> taille <?= htmlspecialchars($bow['size_name']) ?></h1>
    <p class="archet-name">“<?= htmlspecialchars($bow['name']) ?>“</p>
    <p class="archet-range"><strong>Gamme :</strong> <?= htmlspecialchars($bow['range_name']) ?></p>-->
    <h1><?= htmlspecialchars($bow['range_name']) ?> “<?= !empty($bow['name']) ? htmlspecialchars($bow['name']) : 'n° ' . htmlspecialchars($bow['id']) ?>“</h1>
    <p>Archet <?= htmlspecialchars($bow['style_name']) ?> pour <?= htmlspecialchars($bow['instrument_name']) ?> 
    taille <?= htmlspecialchars($bow['size_name']) ?></p>
    <!-- Bloc prix + statut -->
    <p class="hero-pricing">
        <?php if ($bow['status'] !== 'sold' && $finalPrice !== null): ?>
            <?php if ($hasDiscount): ?>
                <span class="price-new"><?= number_format($finalPrice / 100, 2, ',', ' ') ?> €</span>
                <span class="price-old"><?= number_format($price / 100, 2, ',', ' ') ?> €</span>
            <?php else: ?>
                <span class="price-std"><?= number_format($finalPrice / 100, 2, ',', ' ') ?> €</span>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($bow['status'] === 'sold'): ?>
            <span class="badge sold">Vendu</span>
        <?php elseif ($bow['status'] === 'unavailable'): ?>
            <span class="badge unavailable">Indisponible</span>
        <?php endif; ?>
    </p>
  </div>
</section>

<main>
 <?php include('../includes/breadcrumbs.php');?>
  <?php if (!empty($photos)) : ?>
  <section class="bloc photos">
    <!--h2><i class="fas fa-image"></i> Photos</h2>-->
    <div class="galerie">
    <?php foreach ($photos as $photo): ?>
      <a href="<?= $web_photo_dir . basename($photo) ?>" data-fancybox="galerie">
        <img src="<?= $web_photo_dir . basename($photo) ?>" alt="Photo">
      </a>
    <?php endforeach; ?>

    </div>
  </section>
  <?php endif; ?>

<div class="fiche-carte">
  <section class="bloc">
    <h2>Identification & Structure</h2>
    <ul>
      <li><strong>Instrument :</strong> <?= $bow['instrument_name'] ?></li>
      <li><strong>Taille :</strong> <?= $bow['size_name'] ?></li>
      <li><strong>Style :</strong> <?= $bow['style_name'] ?></li>
      <li><strong>Forme :</strong> <?= $bow['shape_name'] ?></li>
      <li><strong>Bois :</strong> <?= $bow['wood_name'] ?> (<?= $bow['origin_name'] ?>)</li>
      <li><strong>Couleur :</strong> <?= $bow['color_name'] ?></li>
      <li><strong>Hausse :</strong> <?= $bow['frog_material_name'] ?></li>
      <li><strong>Coulisse :</strong> <?= $bow['slide_material_name'] ?></li>
      <li><strong>Bouton :</strong> <?= $bow['button_material_name'] ?></li>
      <li><strong>Pointe :</strong> <?= $bow['tip_material_name'] ?></li>
      <li><strong>Garniture :</strong> <?= $bow['garnish_name'] ?></li>
    </ul>
  </section>

  <section class="bloc">
    <h2>Caractéristiques physiques</h2>
    <table>
      <tr><th>Poids baguette</th><td><?= $bow['stick_weight'] ?> g</td></tr>
      <tr><th>Poids total</th><td><?= $bow['total_weight'] ?> g</td></tr>
      <tr><th>Longueur baguette</th><td><?= $bow['stick_length'] ?> mm</td></tr>
      <tr><th>Longueur totale</th><td><?= $bow['total_length'] ?> mm</td></tr>
      <tr><th>Équilibre</th><td><?= $bow['balance_point'] ?> mm</td></tr>
      <tr><th>Densité</th><td><?= $bow['density'] ?> g/cm³</td></tr>
      <tr><th>Vitesse du son</th><td><?= $bow['speed'] ?> m/s</td></tr>
      <tr><th>Élasticité</th><td><?= $bow['elasticity'] ?> GPa</td></tr>
      <tr><th>Fréquence</th><td><?= $bow['frequency'] ?> Hz</td></tr>
      <tr><th>Amortissement</th><td><?= $bow['damping'] ?></td></tr>
    </table>
  </section>

  <section class="bloc">
    <h2>Caractère & Jouabilité</h2>
    <ul>
      <li><strong>Flexibilité :</strong> <?= $bow['flexibility_name'] ?></li>
      <li><strong>Réactivité :</strong> <?= $bow['responsiveness_name'] ?></li>
      <li><strong>Maniabilité :</strong> <?= $bow['handling_name'] ?></li>
      <li><strong>Pression naturelle :</strong> <?= $bow['natural_pressure_name'] ?></li>
    </ul>
    <h2>Sonorité & Expression</h2>
    <ul>
      <li><strong>Timbre dominant :</strong> <?= $bow['tone_name'] ?></li>
      <li><strong>Projection :</strong> <?= $bow['projection_name'] ?></li>
      <li><strong>Sustain :</strong> <?= $bow['sustain_name'] ?></li>
      <li><strong>Articulation :</strong> <?= $bow['articulation_name'] ?></li>
    </ul>
  </section>
</div>

  <section class="bloc notes">
    <h2><i class="fas fa-pen-nib"></i> Notes</h2>
    <p><?= nl2br(htmlspecialchars($bow['notes'])) ?></p>
    <p><a href="https://ivoincidit.fr/scripta/article.php?slug=evaluer-un-archet-methode-ivo-incidit" target="_blank">Comment j'évalue les propriétés d'un archet</a></p>
  </section>
</main>

</div>
  <!-- footer du site -->
  <?php include('../includes/footer.php'); ?>

</body>
</html>
