<?php 
require_once __DIR__ . '/../includes/secure_functions.php';
ensure_admin(); 
?>

<!DOCTYPE html>
<html lang="fr">
  <!-- Head du site -->
  <?php include('../includes/head.php'); ?>
  <title>Tableau Vitesses Lucchi</title>
  <link rel="stylesheet" href="../css/admin.css">
  <style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 4px 6px; text-align: right; }
    th { background: #f0f0f0; }
  </style>
</head>
<body>

  <!-- Header du site -->
  <?php include('../includes/header.php'); ?>
  <p><a href="index.php">← Retour au tableau de bord</a></p>
  <h1>Tableau Vitesses Lucchi</h1>
  <main class="table-critere">
<section id="intro"> <h2>Introduction et signification des colonnes</h2> <p> Ce tableau présente les vitesses longitudinales obtenues par la méthode micro + Audacity, puis ajustées pour tenir compte de la masse de la tête et converties vers les valeurs comparables aux mesures « Lucchi ». Chaque colonne décrit une étape de la conversion : de la mesure brute à la valeur finale adaptée. </p> <dl> <dt><strong>Longueur (mm)</strong></dt> <dd>Longueur vibrante utile de la baguette, exprimée en millimètres — mesurer la longueur sans la tête (longueur effective utilisée dans le calcul).</dd>
<dt><strong>Fréquence (Hz)</strong></dt>
<dd>Fréquence fondamentale relevée dans Audacity (FFT) sur l’impulsion enregistrée. Choisir le pic correspondant au mode longitudinal (Hz).</dd>

<dt><strong>V brute (m/s)</strong></dt>
<dd>Vitesse calculée directement à partir de la mesure : <code>v_brute = 2 · L · f</code> (L en mètres, f en Hz).</dd>

<dt><strong>V corrigée (m/s)</strong></dt>
<dd>Vitesse brute corrigée pour l’effet d’une masse concentrée en tête : <code>v_corr = v_brute · (1 + 0.73 · f_tête)</code>, où <code>f_tête = m_tête / m_totale</code> (fraction de masse de la tête).</dd>

<dt><strong>V Lucchi (m/s)</strong></dt>
<dd>Vitesse finale adaptée aux valeurs Lucchi via un coefficient empirique : <code>v_Lucchi = v_corr · k</code> (ici <code>k ≈ 1.061</code>).</dd>
</dl> </section>
  <table id="lucchiTable">
    <thead>
      <tr>
        <th>Longueur (mm)</th>
        <th>Fréquence (Hz)</th>
        <th>V brute (m/s)</th>
        <th>V corrigée (m/s)</th>
        <th>V Lucchi (m/s)</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

<script>
    // --- Constantes physiques utilisées pour les calculs ---
    const f_tete = 0.0334;        // correction liée à la tête de l’archet (valeur fixe)
    const facteur_tete = 0.73;    // facteur appliqué à f_tete
    const coef_lucchi = 1.061;    // coefficient pour obtenir la "vitesse Lucchi"

    // --- On cible le <tbody> de la table pour insérer les lignes dynamiquement ---
    const tableBody = document.querySelector("#lucchiTable tbody");

    // --- Double boucle : on balaye les longueurs et les fréquences ---
    for (let L_mm = 700; L_mm <= 745; L_mm += 1) {   // Longueur de 700 à 745 mm (pas de 1 mm)
      let L_m = L_mm / 1000;                         // Conversion en mètres pour les calculs

      for (let f = 2900; f <= 3500; f += 10) {       // Fréquence de 2900 à 3500 Hz (pas de 10 Hz)
        
        // --- Formules de calcul ---
        let v_brute = 2 * L_m * f;                                    // vitesse brute
        let v_corr = v_brute * (1 + facteur_tete * f_tete);           // vitesse corrigée
        let v_lucchi = v_corr * coef_lucchi;                          // vitesse Lucchi finale

        // --- Construction de la ligne HTML avec les valeurs ---
        let row = `<tr>
          <td>${L_mm}</td>                          <!-- longueur en mm -->
          <td>${f}</td>                             <!-- fréquence en Hz -->
          <td>${v_brute.toFixed(2)}</td>            <!-- vitesse brute -->
          <td>${v_corr.toFixed(2)}</td>             <!-- vitesse corrigée -->
          <td>${v_lucchi.toFixed(2)}</td>           <!-- vitesse Lucchi -->
        </tr>`;

        // --- On insère la ligne dans le tableau ---
        tableBody.insertAdjacentHTML("beforeend", row);
      }
    }
  </script>
  </main>
  <!-- footer du site -->
  <?php include('../includes/footer.php'); ?>

</body>
</html>