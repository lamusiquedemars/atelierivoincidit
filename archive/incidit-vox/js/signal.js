// js/signal.js

/**
 * Analyse le signal complet : traitement principal à compléter.
 * @param {Float32Array} data - Signal brut en données numériques
 */
function analyzeSignal(data) {
    // Étape 1 : Centrage du signal (soustraction de la moyenne)
    const centered = centerSignal(data);
  
    // Étape 2 : Application du filtre passe-haut à 150 Hz
    const sampleRate = 2000; // Hz, fixe pour l'instant
    const filtered = highPassFilter(centered, 150, sampleRate);
  
    // Étape 3 : Calcul de la FFT (spectre en fréquence)
    const { frequencies, spectrum } = computeFFT(filtered, sampleRate);
  
    // Étape 4 : Détection des pics significatifs entre 180 et 330 Hz
    const peaks = detectPeaks(frequencies, spectrum, 180, 330);
  
    // Étape 5 : Filtrage des pics isolés (aucun autre pic à ±15 Hz)
    const filteredPeaks = filterIsolatedPeaks(peaks, 15);
  
    // Étape 6 : Sélection des 10 pics les plus forts en amplitude
    const top10 = selectTopPeaks(filteredPeaks, 10);
  
    // Étape 7 : Regroupement des pics en grappes (écart < 20 Hz)
    const clusters = groupClusters(top10, 20);
  
    // Étape 8 : Sélection de la grappe la plus haute (si amplitude suffisante)
    const { bestCluster, warning } = selectBestCluster(clusters, top10);
  
    // Étape 9 : Calcul de la zone de résonance probable (min/max de la grappe)
    let resonanceZone = null;
    if (bestCluster && bestCluster.length > 0) {
      const freqs = bestCluster.points.map(p => p.freq);
      resonanceZone = {
        min: Math.min(...freqs),
        max: Math.max(...freqs)
      };
    }
  
    // Étape 10 : Calcul de l’amortissement logarithmique δ à partir de l’enveloppe de Hilbert
    //const damping = computeDampingFromEnvelope(filtered, sampleRate);   
    const damping = computeBestSingleLogDecrement(filtered, sampleRate);
  
   //console.log("Top 10 pics:", top10);
    //console.log("Grappes détectées:", clusters);
    //console.log("Grappe sélectionnée:", bestCluster);
    if (warning) {
      console.warn("⚠️ Grappe haute sélectionnée mais faible en amplitude (sous 70% du max)");
    }
    console.log("Zone de résonance probable:", resonanceZone);
    console.log("Amortissement estimé δ:", damping);
  
    // Étape 11 : Affichage du résumé
    displaySummary(bestCluster, resonanceZone, damping, warning);
    displayTopPeaks(top10);

    // Étape 12 : Affichage du graphe
    drawSpectrum(frequencies, spectrum, top10, bestCluster, resonanceZone);

  }
  
  /**
   * Centre un signal autour de zéro en retirant la moyenne.
   * @param {Float32Array} data - Signal d'entrée
   * @returns {Float32Array} - Signal centré
   */
  function centerSignal(data) {
    const mean = data.reduce((sum, x) => sum + x, 0) / data.length;
    return data.map(x => x - mean);
  }
  
  /**
   * Filtre passe-haut très simple (soustracteur de moyenne locale).
   * Remplace un vrai Butterworth pour une version JS légère.
   * @param {Float32Array} data - Signal d'entrée
   * @param {number} cutoffHz - Fréquence de coupure en Hz
   * @param {number} sampleRate - Taux d'échantillonnage
   * @returns {Float32Array} - Signal filtré
   */
  function highPassFilter(data, cutoffHz, sampleRate) {
    const rc = 1.0 / (2 * Math.PI * cutoffHz);
    const dt = 1.0 / sampleRate;
    const alpha = rc / (rc + dt);
    const output = new Float32Array(data.length);
    output[0] = data[0];
    for (let i = 1; i < data.length; i++) {
      output[i] = alpha * (output[i - 1] + data[i] - data[i - 1]);
    }
    return output;
  }
  
  /**
   * Calcule l’amortissement logarithmique δ à partir de l’enveloppe du signal.
   * @param {Float32Array} signal - Signal filtré
   * @param {number} sampleRate - Fréquence d’échantillonnage
   * @returns {number|null} - δ ou null si non exploitable
   */
  function computeDampingFromEnvelope(signal, sampleRate) {
    const envelope = new Float32Array(signal.length);
  
    // Approximation de l’enveloppe par énergie locale (filtrage simple)
    for (let i = 0; i < signal.length; i++) {
      envelope[i] = Math.abs(signal[i]);
    }
  
    // Lissage par moyenne glissante (optionnel)
    const windowSize = Math.floor(sampleRate / 100); // ~10 ms
    for (let i = 0; i < signal.length - windowSize; i++) {
      let sum = 0;
      for (let j = 0; j < windowSize; j++) {
        sum += envelope[i + j];
      }
      envelope[i] = sum / windowSize;
    }
  
    // Extraction des pics de l’enveloppe (créneaux de décroissance)
    const peaks = [];
    for (let i = 1; i < envelope.length - 1; i++) {
      if (envelope[i] > envelope[i - 1] && envelope[i] > envelope[i + 1]) {
        peaks.push({ x: i, y: envelope[i] });
      }
    }
  
    // Besoin d'au moins 3 pics pour estimer une pente
    if (peaks.length < 3) return null;
  
    const logs = peaks.map(p => Math.log(p.y)).filter(v => isFinite(v));
    const indices = peaks.map(p => p.x / sampleRate);
  
    let slopes = [];
    for (let i = 1; i < logs.length; i++) {
      const dx = indices[i] - indices[i - 1];
      const dy = logs[i] - logs[i - 1];
      if (dx > 0) slopes.push(dy / dx);
    }
  
    const avgSlope = slopes.reduce((sum, s) => sum + s, 0) / slopes.length;
    return -avgSlope > 0 ? -avgSlope : null;
  }
  
//fct amortissement
function computeBestSingleLogDecrement(signal, sampleRate) {
    const envelope = new Float32Array(signal.length);
  
    // Enveloppe simple
    for (let i = 0; i < signal.length; i++) {
      envelope[i] = Math.abs(signal[i]);
    }
  
    // Lissage (~5 ms)
    const windowSize = Math.floor(sampleRate / 200);
    for (let i = 0; i < envelope.length - windowSize; i++) {
      let sum = 0;
      for (let j = 0; j < windowSize; j++) {
        sum += envelope[i + j];
      }
      envelope[i] = sum / windowSize;
    }
  
    // Pics valides (espacement ≥ 20 ms)
    const peaks = [];
    const minSpacing = Math.floor(sampleRate * 0.02);
    let lastPeakIndex = -minSpacing;
    for (let i = 1; i < envelope.length - 1; i++) {
      if (
        envelope[i] > envelope[i - 1] &&
        envelope[i] > envelope[i + 1] &&
        i - lastPeakIndex >= minSpacing
      ) {
        const y = envelope[i];
        if (isFinite(y) && y > 0 && y < 1) {
          peaks.push({ x: i, y });
          lastPeakIndex = i;
        }
      }
    }
  
    if (peaks.length < 2) return null;
  
    // Cherche le plus petit δ dans une plage cohérente
    const deltas = [];
    for (let i = 1; i < peaks.length; i++) {
      const A1 = peaks[i - 1].y;
      const A2 = peaks[i].y;
      const ratio = A2 / A1;
      if (A1 > 0 && A2 > 0 && ratio > 0.2 && ratio < 0.98) {
        const d = Math.log(A1 / A2);
        if (d > 0.004 && d < 0.06) {
          deltas.push({ d, A1, A2, i });
        }
      }
    }
  
    if (deltas.length === 0) {
      console.log("Aucune paire retenue.");
      return null;
    }
  
    // Trie par valeur croissante de δ, retient la meilleure
    deltas.sort((a, b) => a.d - b.d);
    const best = deltas[0];
  
    console.log("✅ Meilleure paire retenue :");
    console.log(`A1=${best.A1.toFixed(5)}, A2=${best.A2.toFixed(5)}, δ=${best.d.toFixed(4)}, index=${best.i}`);
  
    return +best.d.toFixed(4);
  }
      
  /**
   * Calcule le spectre d'amplitude via FFT (simple magnitude).
   * @param {Float32Array} signal - Signal temporel
   * @param {number} sampleRate - Fréquence d'échantillonnage
   * @returns {Object} - { frequencies[], spectrum[] } avec les valeurs utiles
   */
// Fonction pour calculer la FFT
function computeFFT(signal, sampleRate) {
    const N = signal.length;
    const re = Array.from(signal);
    const im = new Array(N).fill(0);

    // Créer une instance de FourierTransform (héritée par FFT)
    const fft = new FFT(N, sampleRate);
  
    // Appliquer les données réelles et imaginaires
    fft.real.set(re);
    fft.imag.set(im);
  
    // Calculer le spectre via la méthode calculateSpectrum() de FourierTransform
    fft.calculateSpectrum(); // La méthode correcte pour obtenir le spectre
  
    // Récupérer le spectre et les fréquences
    const spectrum = fft.spectrum;
    const scaledSpectrum = spectrum.slice(0, N / 2).map(x => +(x * 10000).toFixed(2));//formatage de l'affichage en dizaines et 2 décimales
    const frequencies = Array.from({ length: N / 2 }, (_, i) => (i * sampleRate) / N);

    return {
      frequencies: frequencies.map(f => +f.toFixed(2)),
      spectrum: scaledSpectrum
    };   
}

  /**
   * Détecte les pics significatifs dans un spectre dans une plage de fréquences.
   * @param {number[]} frequencies - Tableau des fréquences (Hz)
   * @param {number[]} spectrum - Amplitudes correspondantes
   * @param {number} fmin - Fréquence minimale à considérer
   * @param {number} fmax - Fréquence maximale à considérer
   * @returns {Array} - Liste d'objets { freq, amp }
   */
  function detectPeaks(frequencies, spectrum, fmin, fmax) {
    const peaks = [];
    for (let i = 1; i < spectrum.length - 1; i++) {
      const f = frequencies[i];
      if (f >= fmin && f <= fmax) {
        const prev = spectrum[i - 1];
        const curr = spectrum[i];
        const next = spectrum[i + 1];
        if (curr > prev && curr > next) {
          peaks.push({ freq: f, amp: curr });
        }
      }
    }
    return peaks;
  }
  
  /**
   * Filtre les pics isolés (aucun autre pic à ±delta Hz).
   * @param {Array} peaks - Liste d'objets { freq, amp }
   * @param {number} delta - Distance minimale pour ne pas être isolé
   * @returns {Array} - Pics conservés (non isolés)
   */
  function filterIsolatedPeaks(peaks, delta) {
    return peaks.filter((p, i) => {
      return peaks.some((q, j) => i !== j && Math.abs(p.freq - q.freq) <= delta);
    });
  }
  
  /**
   * Sélectionne les N pics avec les amplitudes les plus fortes.
   * @param {Array} peaks - Liste de pics { freq, amp }
   * @param {number} n - Nombre de pics à conserver
   * @returns {Array} - Tableau trié des N pics les plus forts
   */
  function selectTopPeaks(peaks, n) {
    // Trier les pics par amplitude décroissante
    const sortedPeaks = peaks.sort((a, b) => b.amp - a.amp);

    // Vérification des amplitudes
    if (sortedPeaks.length === 0) {
        console.warn("Aucun pic détecté.");
    }

    // Log des amplitudes et fréquences
    //console.log("Amplitudes détectées:", sortedPeaks.map(p => p.amp));

    // Sélectionner les n premiers pics (les plus forts)
    const topPeaks = sortedPeaks.slice(0, n);

    // Afficher les 10 pics principaux pour déboguer
    console.log("Top 10 pics sélectionnés:", topPeaks);

    return topPeaks;
}

  
  /**
   * Regroupe les pics en grappes de fréquences proches (moins de delta Hz d'écart).
   * @param {Array} peaks - Liste d'objets { freq, amp }
   * @param {number} delta - Distance max entre deux pics pour les grouper
   * @returns {Array<Array>} - Liste de grappes (tableaux de pics avec .avgFreq/.avgAmp)
   */
  function groupClusters(peaks, delta) {
    const sorted = [...peaks].sort((a, b) => a.freq - b.freq);
    const rawClusters = [];
    let current = [sorted[0]];
  
    for (let i = 1; i < sorted.length; i++) {
      if (sorted[i].freq - sorted[i - 1].freq < delta) {
        current.push(sorted[i]);
      } else {
        rawClusters.push(current);
        current = [sorted[i]];
      }
    }
    rawClusters.push(current);
  
    const clusters = rawClusters.map(cluster => {
      const maxAmp = Math.max(...cluster.map(p => p.amp)); // ✅ ici c'était manquant
      const kept = cluster.filter(p => p.amp >= 0.5 * maxAmp);
      const removed = cluster.filter(p => !kept.includes(p));
  
      const freqs = kept.map(p => p.freq);
      const amps = kept.map(p => p.amp);
      const sortedAmps = [...amps].sort((a, b) => a - b);
      const mid = Math.floor(sortedAmps.length / 2);
      const medianAmp = sortedAmps.length % 2 !== 0
        ? sortedAmps[mid]
        : (sortedAmps[mid - 1] + sortedAmps[mid]) / 2;
  
      //console.log(`🧹 Nettoyage cluster :`);
      //console.log(`  Avant :`, cluster.map(p => `${p.freq.toFixed(1)}Hz @ ${p.amp.toFixed(2)}`));
      //console.log(`  Gardé :`, kept.map(p => `${p.freq.toFixed(1)}Hz @ ${p.amp.toFixed(2)}`));
      //console.log(`  Supprimé :`, removed.map(p => `${p.freq.toFixed(1)}Hz @ ${p.amp.toFixed(2)}`));
  
      return {
        points: kept,
        avgFreq: freqs.reduce((a, b) => a + b, 0) / freqs.length,
        avgAmp: amps.reduce((a, b) => a + b, 0) / amps.length,
        medAmp: medianAmp,
        length: kept.length
      };
    });
  
  
    return clusters;
  }
  
  
  /**
   * Sélectionne la grappe avec la fréquence moyenne la plus haute,
   * si son amplitude moyenne atteint au moins 70% de l'amplitude max du top 10.
   * @param {Array} clusters - Liste de grappes avec avgFreq et avgAmp
   * @param {Array} topPeaks - Liste des 10 pics les plus forts
   * @returns {Object} - { bestCluster, warning }
   */
  function selectBestCluster(clusters, topPeaks) {
    const maxAmp = Math.max(...topPeaks.map(p => p.amp));
    const threshold = 0.7 * maxAmp;
  
    let validClusters = clusters.filter(c => c.medAmp >= threshold);
    let bestCluster = null;
    let warning = false;
  
    if (validClusters.length > 0) {
      // Trie : 1. plus forte en amplitude, 2. plus de pics, 3. plus haute en fréquence
      validClusters.sort((a, b) =>
        b.avgAmp - a.avgAmp ||       // amplitude moyenne décroissante
        b.count - a.count ||         // nombre de pics décroissant
        b.avgFreq - a.avgFreq        // fréquence moyenne décroissante
      );
      bestCluster = validClusters[0];
    } else if (clusters.length > 0) {
      // aucune ne dépasse le seuil → on garde quand même la plus haute
      warning = true;
      clusters.sort((a, b) => b.avgFreq - a.avgFreq);
      bestCluster = clusters[0];
    }
    /*console.log("🔍 Clusters valides (amp ≥ seuil):");
    validClusters.forEach(c => {
      console.log(`→ avgAmp=${c.avgAmp.toFixed(2)} | length=${c.length} | avgFreq=${c.avgFreq.toFixed(2)}`);
    });
    
    if (bestCluster) {
      console.log("✅ Best cluster sélectionné :");
      console.log(`→ avgAmp=${bestCluster.avgAmp.toFixed(2)} | length=${bestCluster.length} | avgFreq=${bestCluster.avgFreq.toFixed(2)}`);
      console.log("→ Points:", bestCluster.points);
    }
    
    if (warning) {
      console.warn("⚠️ Aucune grappe ne dépasse le seuil. Cluster le plus haut retenu malgré tout.");
    }*/
    
    return { bestCluster, warning };
  }

  /**
 * Affiche un résumé des résultats dans la page (élément #summary).
 * @param {Array|null} cluster - Grappe sélectionnée ou null
 * @param {Object|null} zone - Objet {min, max} ou null
 * @param {number|null} damping - Valeur de δ ou null
 * @param {boolean} warning - Alerte sur l'amplitude faible
 */
function displaySummary(cluster, zone, damping, warning) {
    const summaryDiv = document.getElementById("summary");
    summaryDiv.innerHTML = "";
    const ul = document.createElement("ul");
  
    if (zone) {
      ul.innerHTML += `<li><strong>Zone de résonance :</strong> ${zone.min.toFixed(1)} – ${zone.max.toFixed(1)} Hz</li>`;
    } else {
      ul.innerHTML += `<li><strong>Zone de résonance :</strong> non détectée ❌</li>`;
    }
  
    if (cluster) {
      ul.innerHTML += `<li><strong>Fréquence moyenne :</strong> ${cluster.avgFreq.toFixed(1)} Hz</li>`;
      ul.innerHTML += `<li><strong>Amplitude moyenne :</strong> ${cluster.avgAmp.toFixed(2)}</li>`;
    }
  
    if (damping !== null) {
      ul.innerHTML += `<li><strong>Amortissement (δ) :</strong> ${damping.toFixed(4)}</li>`;
    } else {
      ul.innerHTML += `<li><strong>Amortissement (δ) :</strong> non exploitable ❌</li>`;
    }
  
    if (warning) {
      ul.innerHTML += `<li><strong>⚠️ Alerte :</strong> la grappe la plus haute est faible en amplitude</li>`;
    }
  
    summaryDiv.appendChild(ul);
  }
  /*affiche les 10 pics*/
  function displayTopPeaks(peaks) {
    const div = document.getElementById("peaks-table");
    div.innerHTML = "";
  
    if (!peaks || peaks.length === 0) {
      div.innerHTML = "<p>Aucun pic détecté.</p>";
      return;
    }
  
    const title = document.createElement("h3");
    title.textContent = "Top 10 des pics détectés";
  
    const ul = document.createElement("ul");
    peaks.forEach((p, i) => {
      const li = document.createElement("li");
      li.innerHTML = `<strong>${i + 1}.</strong> ${p.freq.toFixed(2)} Hz – ${p.amp.toFixed(2)}`;
      ul.appendChild(li);
    });
  
    div.appendChild(title);
    div.appendChild(ul);
  }
  
  /**
 * Dessine le spectre FFT avec les pics et la grappe sélectionnée.
 * @param {number[]} frequencies - Tableau des fréquences (Hz)
 * @param {number[]} spectrum - Amplitudes correspondantes
 * @param {Array} peaks - Pics détectés { freq, amp }
 * @param {Array|null} bestCluster - Grappe sélectionnée
 * @param {Object|null} resonanceZone - {min, max}
 */
function drawSpectrum(frequencies, spectrum, peaks, bestCluster, resonanceZone) {
    const canvas = document.getElementById("fft-canvas");
    if (!canvas) return;
    const ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
  
    const fmin = 180;
    const fmax = 330;
    const ampMax = Math.max(...spectrum);
  
    // Marges internes
    const padding = 40;
    const w = canvas.width - 2 * padding;
    const h = canvas.height - 2 * padding;
  
    // Axes
    ctx.strokeStyle = "#999";
    ctx.beginPath();
    ctx.moveTo(padding, padding);
    ctx.lineTo(padding, canvas.height - padding);
    ctx.lineTo(canvas.width - padding, canvas.height - padding);
    ctx.stroke();
  
    // Zone de résonance
    if (resonanceZone) {
      const x1 = padding + ((resonanceZone.min - fmin) / (fmax - fmin)) * w;
      const x2 = padding + ((resonanceZone.max - fmin) / (fmax - fmin)) * w;
      ctx.fillStyle = "#ffccaa44";
      ctx.fillRect(x1, padding, x2 - x1, h);
    }
  
    // Courbe FFT
    ctx.beginPath();
    ctx.strokeStyle = "#333";
    for (let i = 0; i < frequencies.length; i++) {
      const f = frequencies[i];
      if (f < fmin || f > fmax) continue;
      const x = padding + ((f - fmin) / (fmax - fmin)) * w;
      const y = canvas.height - padding - (spectrum[i] / ampMax) * h;
      if (i === 0) ctx.moveTo(x, y);
      else ctx.lineTo(x, y);
    }
    ctx.stroke();
  
    // Pics
    peaks.forEach(p => {
      if (p.freq < fmin || p.freq > fmax) return;
      const x = padding + ((p.freq - fmin) / (fmax - fmin)) * w;
      const y = canvas.height - padding - (p.amp / ampMax) * h;
      ctx.fillStyle = "#0077cc";
      ctx.beginPath();
      ctx.arc(x, y, 3, 0, 2 * Math.PI);
      ctx.fill();
    });
  
    // Grappe sélectionnée
    if (bestCluster) {
      bestCluster.points.forEach(p => {
        const x = padding + ((p.freq - fmin) / (fmax - fmin)) * w;
        const y = canvas.height - padding - (p.amp / ampMax) * h;
        ctx.fillStyle = "#cc2200";
        ctx.beginPath();
        ctx.arc(x, y, 4, 0, 2 * Math.PI);
        ctx.fill();
      });
    }
  }
  