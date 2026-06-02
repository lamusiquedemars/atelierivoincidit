<?php

/**
 * Page d’accueil.
 * Page d’entrée principale du site.
 * Elle présente l’atelier, les familles d’archets
 * et oriente naturellement vers l’essai.
 */

$title = 'Atelier Ivo Incidit';
$description = 'Archets contemporains fabriqués à Lyon, en bois brésiliens alternatifs.';
$bodyClass = 'page-home';

render('hero', [
    'title' => $title,
    'subtitle' => $description,
    'class' => ['hero--full'],
    'link' => '',
    'label' => ''
]);
?>

<section class="section--gradient">
    <div class="container">
        <div class="bloc-section">
            <div class="split">
                <div class="split__item">
                    <p>
                        Je suis Ivo Correia de Melo, archetier à Lyon. J’ai nommé mon atelier
                        <em>Incidit</em> en hommage à Ivo Incisi, luthier italien passé par le Brésil
                        au début du XX<sup>e</sup> siècle.
                    </p>

                    <p>
                        Né à Recife, dans l’État de Pernambouc, au Brésil, je fabrique des archets
                        en explorant des bois brésiliens alternatifs au pernambouc. Mon travail s'inspire de la tradition française,
                        dans une logique d'évolution et contemporaine.
                    </p>
                    <p>
                        Aussi violoniste, je conçois mes archets avant tout comme des outils de jeu, au service de la performance.
                    </p>
                </div>

                <div class="split__item">
                    <img src="<?= e(img('archets-colores.jpeg')) ?>" alt="Atelier Ivo Incidit">
                </div>
            </div>
        </div>
</section>

<section class="home-arcus-preview">
    <div class="container">
        <?php
        $showcase = require app_path('data/showcase.php');
        render('showcase', [
            'title' => 'Galerie d‘atelier',
            'intro' => 'Quelques archets réalisés récemment.',
            'layout' => 'carousel',
            'lightbox' => true,
            'items' => $showcase,
        ]);
        ?>
    </div>
</section>

<section class="home-arcus">
    <div class="container">
        <h2>Mes trois approches de l’archet</h2>

        <div class="grid grid--3">
            <article class="card">
                <a href="<?= e(url('/arcus/ars-antiqua')) ?>">
                    <img class="card-image" src="<?= e(img('home-antiqua.HEIC')) ?>" alt="Archet Ars Antiqua">
                    <h3>Ars Antiqua</h3>
                    <p>
                        Archets pour la musique ancienne, mais non seulement...
                    </p>
                </a>
            </article>

            <article class="card">
                <a href="<?= e(url('/arcus/ars-classica')) ?>">
                    <img class="card-image" src="<?= e(img('home-classica.HEIC')) ?>" alt="Archet Ars Classica">
                    <h3>Ars Classica</h3>
                    <p>
                        Archets classiques, formes et sensations classiques.
                    </p>
                </a>
            </article>

            <article class="card">
                <a href="<?= e(url('/arcus/ars-nova')) ?>">
                    <img class="card-image" src="<?= e(img('home-nova.HEIC')) ?>" alt="Archet Ars Nova">
                    <h3>Ars Nova</h3>
                    <p>
                        La nouvelle archèterie.
                    </p>
                </a>
            </article>
        </div>
    </div>
</section>

<section class="home-essay">
    <div class="container">
        <h2>Essayer avant de choisir</h2>
        <p>
            Un archet ne se juge pas seulement sur une fiche ou une photographie.
            Il se comprend en main, avec un instrument, un geste, une manière de jouer.
        </p>
        <p>
            Les archets disponibles peuvent être essayés à Lyon ou envoyés pour essai,
            après un premier échange.
        </p>
        <p class="cta">
            <a class="btn" href="<?= e(url('/essai')) ?>">
                Comprendre l’essai
            </a>
        </p>
    </div>
</section>

<section class="home-materials">
    <div class="container">
        <div class="split">
            <div class="split__item">
                <img src="<?= e(img('tetes-archets.jpeg')) ?>" alt="Bois brésiliens utilisés pour les archets">
            </div>
            <div class="split__item">
                <h2>Bois et matériaux utiles</h2>
                <p>
                    Mon travail part d’une croyance simple : on peut fabriquer des archets sérieux
                    avec d’autres bois que le pernambouc. J’explore des essences brésiliennes
                    choisies pour leurs propriétés mécaniques et acoustiques et leur réponse sous la main.
                </p>
                <p>
                    Cette démarche concerne aussi les autres parties de l’archet.
                    Chaque matériau doit avoir un sens, une utilité.
                </p>
                <p class="cta">
                    <a class="btn btn-secondary" href="<?= e(url('/archetier')) ?>">
                        Découvrir l’atelier
                    </a>
                </p>
            </div>
        </div>
    </div>
</section>

<section class="section--gradient home-testimonials">
    <div class="container">
        <?php
        $quotes = require app_path('data/quotes.php');

        render('QuoteCarousel', [
            'kicker' => 'Quelques retours de musiciens',
            'title' => '',
            'variant' => 'editorial',
            'items' => $quotes,
        ]);
        ?>
    </div>
</section>