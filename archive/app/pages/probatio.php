<?php

/**
 * Page Essayer un archet.
 * Page de réassurance et de contact : elle explique pourquoi
 * l’essai est normal, comment il se déroule, et oriente vers un échange.
 * Elle ne charge pas les archets depuis la base.
 */
$title = 'Essayer un archet';
$description = 'Essayer un archet artisanal Ivo Incidit à Lyon ou à distance, avec conseil avant choix.';
$bodyClass = 'page-essai';

/*HERO*/
render('hero', [
    'title' => 'Essayer un archet',
    'subtitle' => 'Choisir avec l’instrument en main.',
    'class' => ['hero-essai'],
    'link' => '',
    'label' => ''
]);
?>
<section class="essai-intro">
    <div class="container">
        <p>
            Un archet se choisit en jouant. L’essai permet de vérifier la réponse,
            l’équilibre, le confort et le rapport réel avec votre instrument.
        </p>
        <p>
            Une fiche peut orienter, mais elle ne remplace pas le geste :
            l’attaque, la tenue sur la corde, les nuances et la sensation en main
            se découvrent surtout en situation de jeu.
        </p>
    </div>
</section>

<section class="essai-criteria">
    <div class="container">
        <h2>Ce que l’essai permet de vérifier</h2>
        <ul class="text-list">
            <li>
                <strong>Réponse à l’attaque :</strong> l’archet doit partir naturellement,
                sans forcer ni retenir le geste.
            </li>
            <li>
                <strong>La tenue sur la corde :</strong> il doit rester stable dans les coups d’archet,
                les changements de nuance et les passages plus exigeants.
            </li>
            <li>
                <strong>L’équilibre en main :</strong> le poids seul ne dit pas tout.
                Ce qui compte, c’est la manière dont l’archet se place dans le jeu.
            </li>
            <li>
                <strong>Le confort dans la durée :</strong> un bon archet ne doit pas seulement séduire
                au premier contact, il doit rester agréable après plusieurs minutes de jeu.
            </li>
            <li>
                <strong>Le son avec votre instrument :</strong> chaque instrument réagit différemment.
                L’essai permet d’entendre ce que l’archet révèle, soutient ou modifie.
            </li>
        </ul>
    </div>
</section>

<section class="essai-process">
    <div class="container">
        <h2>Comment se passe l’essai ?</h2>
        <p>
            Le plus simple est de commencer par un premier échange.
            Vous me dites quel instrument vous jouez, votre niveau, votre pratique,
            et ce que vous cherchez dans un archet.
        </p>
        <p>
            Je peux ensuite vous orienter vers un ou plusieurs archets possibles.
            L’essai peut se faire à Lyon ou, lorsque c’est possible, à distance par envoi.
        </p>
        <p>
            La décision vient après l’essai. Il est normal de comparer, d’hésiter,
            ou de revenir vers moi avec des impressions encore imprécises.
        </p>
    </div>
</section>

<section class="essai-lyon">
    <div class="container">
        <h2>Essai à Lyon ou à distance</h2>
        <div class="split">
            <div class="split__item">
                <h3>Essai à Lyon</h3>
                <p>
                    L’essai peut se faire à Lyon ou en région lyonnaise, selon les possibilités
                    et les modalités convenues ensemble.
                </p>
                <p>
                    L’objectif est simple : prendre le temps de jouer, d’écouter,
                    de comparer les sensations et de voir si un archet correspond vraiment
                    à votre instrument.
                </p>
            </div>
            <div class="split__item">
                <h3>Essai à distance</h3>
                <p>
                    Si vous n’êtes pas près de Lyon, un essai par envoi peut être envisagé
                    après un premier échange.
                </p>
                <p>
                    Les conditions pratiques sont définies avant l’envoi :
                    choix de l’archet, durée d’essai, expédition, retour et précautions nécessaires.
                </p>
                <p>
                    Rien n’est automatique : l’idée est de trouver une solution claire,
                    simple et adaptée à la situation.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="essai-advice section--gradient">
    <div class="container">
        <h2>Si vous ne savez pas quel archet essayer</h2>
        <p>
            Écrivez-moi simplement avec quelques informations :
            votre instrument, votre niveau, votre pratique, ce que vous aimez
            ou n’aimez pas dans votre archet actuel, et ce que vous recherchez.
        </p>
        <p>
            Je vous orienterai vers un ou deux archets possibles, sans vous demander
            de choisir seul à partir d’une fiche technique.
        </p>
        <p class="cta">
            <a class="btn" href="<?= e(url('/contact')) ?>">
                Me demander conseil
            </a>
        </p>
        <h3>L’essai sert justement à décider</h3>
        <p>
            Vous n’avez pas besoin d’arriver avec une idée parfaitement formulée.
            Il est normal de comparer, d’hésiter, de chercher les mots justes
            pour décrire une sensation de jeu.
        </p>
        <p>
            L’essai est là pour cela : transformer une intuition en décision plus claire,
            avec l’archet et l’instrument en main.
        </p>
    </div>
</section>