// Fonction pour transformer le texte en tableau de nombres
function parseTxtFile(rawText) {
    return rawText
      .split(/[\s,;]+/)
      .map(Number)
      .filter(v => !isNaN(v));
  }
  
  // Ajoute un écouteur d'événement sur le bouton "Analyser"
  document.getElementById("analyze-btn").addEventListener("click", () => {
    const fileInput = document.getElementById("file-input");
    const file = fileInput.files[0]; // Récupère le fichier sélectionné
  
    if (!file) {
      alert("Veuillez choisir un fichier .txt à analyser.");
      return; // Arrête si aucun fichier n'est sélectionné
    }
  
    const reader = new FileReader(); // Initialise un lecteur de fichier
  
    // Fonction déclenchée une fois que le fichier est lu
    reader.onload = function (e) {
      const rawText = e.target.result; // Contenu brut du fichier texte
      const data = parseTxtFile(rawText); // Transforme le texte en tableau numérique
  
      if (!data || data.length === 0) {
        alert("Le fichier ne contient pas de données exploitables.");
        return; // Arrête si le fichier est vide ou incorrect
      }
  
      console.log("Données importées (échantillons):", data.slice(0, 10)); // Affiche les 10 premiers échantillons pour vérification
      analyzeSignal(new Float32Array(data)); // Appelle la fonction d'analyse (implémentée dans signal.js)
    };
  
    reader.readAsText(file); // Lit le fichier comme texte brut
  });
  