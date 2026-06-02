<?php

/**
 * Page contact
 *
 * Objectif :
 * permettre à un visiteur de demander un essai, un conseil
 * ou un échange autour d’un archet, sans se sentir obligé
 * d’avoir déjà choisi le bon modèle.
 */
$title = 'Contact';
$description = 'Contacter Ivo Incidit, atelier d’archeterie artisanale près de Lyon.';
$bodyClass = 'page-contact';

/*<!-- HERO -->*/
render('hero', [
  'title' => 'Essayer un archet',
  'subtitle' => 'Contactez-moi pour un essai, un conseil, ou choisir un archet adapté à votre jeu.',
  'class' => ['hero-contact'],
  'link' => '',
  'label' => ''
]);
?>

<section class="">
  <div class="container">
    <h2>Pourquoi me contacter&nbsp;?</h2>
    <p>
      Il n’est pas nécessaire de savoir exactement quel archet choisir avant d’écrire.
      Vous pouvez simplement me décrire votre instrument, votre jeu, votre budget,
      ou l’archet qui vous intéresse.
    </p>
    <ul class="contact-reasons">
      <li>
        <strong>Essayer un archet</strong>
        <span>Organiser un essai à l’atelier, près de Lyon, ou envisager un envoi lorsque c’est possible.</span>
      </li>

      <li>
        <strong>Demander conseil</strong>
        <span>Choisir entre Ars Antiqua, Ars Classica et Ars Nova, ou mieux cerner le type d’archet adapté à votre jeu.</span>
      </li>

      <li>
        <strong>Parler d’une commande</strong>
        <span>Évoquer une fabrication à venir, une recherche particulière, ou un archet qui n’est pas actuellement disponible.</span>
      </li>
    </ul>
  </div>
</section>

<section class="">
  <div class="container">
    <div class="split">
      <div class="split__item">
        <h2>Écrire à l’atelier</h2>
        <p>
          Le plus simple est de m’envoyer un message en précisant votre instrument,
          votre niveau ou pratique musicale, et ce que vous cherchez à améliorer
          ou à retrouver dans un archet.
        </p>
        <p>
          Si vous avez déjà repéré un archet sur le site, indiquez simplement son nom
          ou son numéro.
        </p>
        <p class="contact-note">
          Adresse directe :
          <a href="mailto:info@atelierivoincidit.fr">info@atelierivoincidit.fr</a>
        </p>
      </div>
      <div class="split__item split__item--portrait">
        <img src="<?= e(img('ivo-correia.HEIC')) ?>" alt="Ivo Correia de Melo, archetier près de Lyon.">
      </div>
    </div>
  </div>
</section>

<section class="">
  <div class="container">
    <h2>Essai et envoi</h2>
    <p>
      Les archets peuvent être essayés à l’atelier, à Collonges-au-Mont-d’Or,
      près de Lyon. Un essai par envoi peut aussi être envisagé selon les cas.
    </p>
    <p>
      Les envois sont réalisés via Colissimo suivi. Les frais et délais ci-dessous
      sont donnés à titre indicatif.
    </p>
    <table class="table-expedition">
      <thead>
        <tr>
          <th>Destination</th>
          <th>Délais estimés</th>
          <th>Frais d’envoi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>France</td>
          <td>2 à 5 jours</td>
          <td>12 €</td>
        </tr>
        <tr>
          <td>Europe</td>
          <td>3 à 7 jours</td>
          <td>20 €</td>
        </tr>
      </tbody>
    </table>
    <p class="contact-note">
      Les conditions détaillées d’essai, d’expédition, de paiement et de garantie
      sont précisées dans les
      <a href="<?= e(url('/cgv')) ?>">Conditions Générales de Vente</a>.
    </p>
  </div>
</section>

<section class="">
  <div class="container">
    <h2>Informations atelier</h2>
    <p><strong>Atelier :</strong> Ivo Incidit</p>
    <p><strong>Artisan :</strong> Ivo Correia de Melo</p>
    <p><strong>Lieu :</strong> Collonges-au-Mont-d’Or, près de Lyon</p>
    <p>
      <strong>Entreprise :</strong>
      Entreprise individuelle immatriculée au Répertoire des Métiers
      <a
        href="https://data.inpi.fr/export/companies?format=pdf&ids=%5B%22894976133%22%5D&est=all"
        target="_blank"
        rel="noopener">
        nº 894 976 133 RM 69
      </a>
    </p>
  </div>
</section>