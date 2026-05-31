// js/utils.js

/**
 * Transforme un texte brut issu d'un fichier .txt
 * en un tableau de nombres flottants (échantillons).
 * Ignore les lignes vides ou non numériques.
 * @param {string} rawText - Le contenu brut du fichier .txt
 * @returns {Float32Array} - Tableau de données numériques nettoyées
 */
function parseTxtFile(rawText) {
    // Sépare le texte en lignes
    const lines = rawText.split(/\r?\n/);
  
    // Filtre les lignes valides et les convertit en nombres
    const values = lines
      .map(line => line.trim())             // Enlève les espaces inutiles
      .filter(line => line !== "")         // Ignore les lignes vides
      .map(line => parseFloat(line))       // Convertit en float
      .filter(val => !isNaN(val));         // Ne garde que les valeurs numériques valides
  
    // Retourne les données sous forme de tableau typé (optimisé pour le calcul)
    return new Float32Array(values);
  }
  