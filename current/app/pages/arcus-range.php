<?php

/**
 * Page d'une série d'archets.
 *
 * Modèle commun pour Ars Antiqua, Ars Classica et Ars Nova.
 * La page porte le discours de série et accueillera ensuite
 * les archets disponibles fournis par app/data/bows.php.
 */

$params = $params ?? [];
$range = $params['range'] ?? null;

$rangeContents = [
    'ars-antiqua' => [
        'title' => 'Ars Antiqua',
        'description' => 'Archets inspirés par les gestes anciens, le jeu baroque et les équilibres historiques.',
        'heroSubtitle' => 'Pour retrouver un geste, une articulation, un répertoire.',
        'introTitle' => 'Un archet pour les musiques anciennes',
        'introText' => 'Ars Antiqua réunit des archets inspirés par les jeux anciens, le baroque et les équilibres plus légers ou plus articulés, 
        selon les modèles. Il ne s’agit pas de promettre des reconstitutions historiques, mais de proposer des archets utiles au geste musical.',
        'intentionTitle' => 'Intention de jeu',
        'intentionText' => 'Cette série met l’accent sur l’articulation, la respiration de la phrase, la souplesse de réponse et le rapport direct à la corde. 
        Ici, le mot important n’est pas seulement “ancien” : c’est le geste.',
        'forWhoTitle' => 'Pour qui ?',
        'forWhoItems' => [
            'Musicien baroque ou curieux du jeu ancien.',
            'Violoniste, altiste ou violoncelliste qui cherche une réponse différente de l’archet moderne.',
            'Amateur ou professionnel qui veut explorer un autre rapport à la corde.',
        ],
        'notTitle' => 'Ce que cette série n’est pas',
        'notText' => 'Ars Antiqua ne désigne pas des archets modernes simplement ayant une esthétique “à l’ancienne”. 
        Certains modèles peuvent être libres dans leur inspiration, d’autres plus proches d’un usage historique, 
        mais tous sont pensés comme des archets de jeu pour un répertoire spécifique.',
        'availableTitle' => 'Archets Ars Antiqua disponibles',
        'emptyText' => 'Les archets Ars Antiqua sont fabriqués selon les disponibilités et les recherches en cours. 
        Vous pouvez me contacter pour parler d’un besoin précis ou d’un prochain archet.',
        'finalTitle' => 'Le geste confirme',
        'finalText' => 'Pour ce type d’archet, les mots donnent une direction ; le geste confirme.',
    ],
    'ars-classica' => [
        'title' => 'Ars Classica',
        'description' => 'Archets pensés pour une pratique moderne régulière, stable et exigeante.',
        'heroSubtitle' => 'Un archet équilibré, fait pour le travail musical quotidien.',
        'introTitle' => 'Un archet pour le jeu courant',
        'introText' => 'Ars Classica réunit des archets pensés pour l’étude avancée, la pratique professionnelle, l’enseignement, la musique de chambre, 
        l’orchestre et le travail quotidien.',
        'intentionTitle' => 'Intention de jeu',
        'intentionText' => 'Cette série s’inscrit dans les standards de l’archèterie conventionnelle, avec des proportions, un équilibre et un montage 
        proches des repères traditionnels.
        Elle s’adresse à des musiciens qui recherchent un archet clair, stable et familier, respectueux de l’esthétique classique et sûr dans la main.',
        'forWhoTitle' => 'Pour qui ?',
        'forWhoItems' => [
            'Étudiant avancé qui sent que son archet actuel le limite.',
            'Amateur engagé qui cherche un archet sérieux et durable.',
            'Professionnel, enseignant ou musicien régulier qui veut un outil fiable.',
        ],
        'notTitle' => 'Ce que cette série n’est pas',
        'notText' => 'Ars Classica n’est pas une série de rupture ou d’expérimentation assumée.
        Elle reste proche des repères traditionnels de l’archèterie : proportions, équilibre, montage et esthétique y servent un archet stable, 
        familier et directement tourné vers l’usage musical quotidien.',
        'availableTitle' => 'Archets Ars Classica disponibles',
        'emptyText' => 'Il n’y a pas toujours un archet Ars Classica disponible immédiatement. Vous pouvez me contacter pour connaître les prochaines fabrications ou me décrire ce que vous cherchez.',
        'finalTitle' => 'Choisir avec l’instrument',
        'finalText' => 'Un archet se choisit avec l’instrument. L’essai permet de vérifier la réponse, l’équilibre et le confort réel.',
    ],
    'ars-nova' => [
        'title' => 'Ars Nova',
        'description' => 'Archets personnels, singuliers, construits autour d’une recherche de matière et de caractère.',
        'heroSubtitle' => 'Une nouvelle esthétique de l‘archet, toujours pensée pour le jeu.',
        'introTitle' => 'La nouvelle voie',
        'introText' => 'Ars Nova rassemble des archets où la recherche de matière, d’équilibre et de caractère est plus visible. 
        Ce sont des archets faits pour des musiciens ouverts à une proposition moins standard, mais toujours pensée pour le jeu.',
        'intentionTitle' => 'Intention de jeu',
        'intentionText' => 'Cette série explore des sensations plus personnelles : une réponse particulière, une couleur, 
        un équilibre moins formaté, une présence différente dans la main. L’objectif reste concret : servir le jeu, musicalement et esthétiquement.',
        'forWhoTitle' => 'Pour qui ?',
        'forWhoItems' => [
            'Amateur engagé qui veut un archet avec une présence particulière.',
            'Musicien curieux des bois et des équilibres moins conventionnels.',
            'Professionnel ou enseignant qui cherche un outil complémentaire, différent de ses repères habituels.',
        ],
        'notTitle' => 'Ce que cette série n’est pas',
        'notText' => 'Ars Nova n’est pas une réponse contre l’archet traditionnel.
        C’est une série construite autour d’autres équilibres de jeu, de son et de matière, 
        sans chercher à reproduire exactement les modèles classiques.',
        'availableTitle' => 'Archets Ars Nova disponibles',
        'emptyText' => 'Les archets Ars Nova sont fabriqués en petites séries ou en pièces uniques. Vous pouvez me contacter pour connaître les prochains archets ou me parler d’une recherche particulière.',
        'finalTitle' => 'Essayer pour juger',
        'finalText' => 'Ars Nova se comprend souvent mieux en main que sur une fiche. L’essai permet de sentir si cette voix d’archet correspond à votre jeu.',
    ],
];

$content = $rangeContents[$range] ?? null;

if ($content === null) {
    http_response_code(404);
    $title = 'Gamme introuvable';
    $description = 'Cette gamme d’archets n’existe pas ou n’est pas encore publiée.';
    $bodyClass = 'page-arcus-range page-not-found';
} else {
    $title = $content['title'];
    $description = $content['description'];
    $bodyClass = 'page-arcus-range page-arcus-range-' . $range;
}

/*<!-- HERO -->*/

render('hero', [
    'title' => $title,
    'subtitle' => $content['heroSubtitle'] ?? '',
    'class' => ['hero-' . $range],
    'link' => '',
    'label' => ''
]);

/*
 * Les archets disponibles */
require app_path('data/bows.php');

$instrumentFilter = trim($_GET['instrument'] ?? '');
$bows = $content !== null ? get_bows_by_range($range, $instrumentFilter) : [];
?>

<?php if ($content === null): ?>
    <section class="">
        <div class="container">
            <p>La gamme demandée est introuvable.</p>

            <p class="cta">
                <a class="button" href="<?= e(url('/arcus')) ?>">
                    Retour aux archets
                </a>
            </p>
        </div>
    </section>
<?php else: ?>

    <section class="range-intro">
        <div class="container">
            <h2><?= e($content['introTitle']) ?></h2>
            <p><?= e($content['introText']) ?></p>

            <h3><?= e($content['intentionTitle']) ?></h3>
            <p><?= e($content['intentionText']) ?></p>

            <h3><?= e($content['forWhoTitle']) ?></h3>

            <ul>
                <?php foreach ($content['forWhoItems'] as $item): ?>
                    <li><?= e($item) ?></li>
                <?php endforeach; ?>
            </ul>

            <h3><?= e($content['notTitle']) ?></h3>
            <p><?= e($content['notText']) ?></p>
        </div>
    </section>

    <section class="range-available">
        <div class="container">
            <h2><?= e($content['availableTitle']) ?></h2>

            <?php if (!empty($bows)): ?>
                <?php require app_path('components/BowGrid.php'); ?>
            <?php else: ?>
                <p><?= e($content['emptyText']) ?></p>

                <p class="cta">
                    <a class="button" href="<?= e(url('/contact')) ?>">
                        Me parler de votre recherche
                    </a>
                </p>
            <?php endif; ?>
        </div>
    </section>

    <section class="range-final">
        <div class="container">
            <h2><?= e($content['finalTitle']) ?></h2>
            <p><?= e($content['finalText']) ?></p>

            <p class="cta">
                <a class="button" href="<?= e(url('/contact')) ?>">
                    Me demander conseil
                </a>
            </p>
        </div>
    </section>

<?php endif; ?>