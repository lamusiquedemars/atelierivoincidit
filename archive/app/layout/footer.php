<?php
/**
 * Pied de page commun du site.
 *
 * Ce fichier contient la fin commune du document HTML.
 * Il pourra accueillir plus tard le footer visible du site.
 */
?>
<footer class="site-footer">
  <p>&copy; Ivo Incidit — Atelier d’Archèterie</p>

  <p>
    <a href="<?= e(url('/mentions-legales')) ?>">
      Mentions légales
    </a>
    •
    <a href="<?= e(url('/cgv')) ?>">
      CGV
    </a>
    •
    <a href="<?= e(url('/contact')) ?>">
      Contact
    </a>
    •
    <a href="https://instagram.com/ivo_incidit" target="_blank" rel="noopener">
      Instagram : @ivo_incidit
    </a>
  </p>
</footer>


</body>
</html>