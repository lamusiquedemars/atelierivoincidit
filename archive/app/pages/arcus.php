<?php

/**
 * Page Archets.
 * Page carrefour : elle présente les séries d'archets
 * et oriente le visiteur vers la série la plus adaptée.
 * Elle ne charge pas les archets depuis la base.
 */
$title = 'Archets';
$description = 'Archets artisanaux faits main par Ivo Correia de Melo : Ars Antiqua, Ars Classica et Ars Nova.';
$bodyClass = 'page-arcus';

/*<!-- HERO -->*/
render('hero', [
    'title' => 'Archets',
    'subtitle' => 'Trois séries, trois intentions de jeu.',
    'class' => ['hero-arcus'],
    'link' => '',
    'label' => ''
]);
?>

<section class="arcus-intro">
    <div class="container">
        <p>
            Mes archets sont conçus pour servir le musicien selon son niveau, son style et sa sensibilité.
            Je propose trois gammes distinctes, basées sur des critères mesurables — densité, élasticité, cambrure et équilibre —
            tout en conservant la même exigence de construction.
            Ce sont des archets adaptés aux amateurs comme aux musiciens professionnels, aux styles anciens comme aux
            interprétations contemporaines.
        </p>
        <p>
            Les noms des gammes <strong>Ars Antiqua</strong>, <strong>Ars Classica</strong> et <strong>Ars Nova</strong> — reflètent cette approche :
            comme dans l'histoire de la musique, je raconte leur histoire à ma façon, avec chaque gamme comme un style différent.
        </p>
        <p>
            Je façonne les baguettes à la main, qui portent les traces du geste et du travail, participant à leur caractère authentique et unique.
        </p>
    </div>
</section>

<section class="arcus-series">
    <div class="container">
        <h2>Trois séries, trois manières de jouer</h2>
        <?php
        $series = require app_path('data/arcus-series.php');

        render('FeatureGrid', [
            'items' => $series,
            'variant' => 'series',
            'columns' => 3,
        ]);
        ?>
    </div>
</section>

<section class="arcus-choice section--gradient">
    <div class="container">
        <h2>Comment choisir ?</h2>
        <ul class="choice-list">
            <li>
                Si vous cherchez un archet baroque ou historiquement inspiré,
                commencez par <strong>Ars Antiqua</strong>.
            </li>
            <li>
                Si vous cherchez un archet stable, équilibré, pensé pour une pratique moderne régulière,
                allez vers <strong>Ars Classica</strong>.
            </li>
            <li>
                Si vous cherchez une proposition plus personnelle, moins standard,
                regardez du côté d’<strong>Ars Nova</strong>.
            </li>
        </ul>
        <p>
            Si vous hésitez, l’échange et l’essai restent souvent plus justes qu’un choix fait uniquement sur fiche.
        </p>

        <h3>Archets disponibles</h3>
        <p>
            Les archets disponibles sont présentés directement dans chaque page de série.
            Cela permet de découvrir les modèles dans leur contexte, sans transformer cette page en catalogue général.
        </p>

        <h3>Choisir avec l’instrument en main</h3>
        <p>
            Les mots peuvent orienter, mais le choix d’un archet se confirme surtout avec l’instrument,
            dans le geste et dans l’écoute.
        </p>
        <p class="cta">
            <a class="btn" href="<?= e(url('/contact')) ?>">
                Me demander conseil
            </a>
        </p>
    </div>
</section>