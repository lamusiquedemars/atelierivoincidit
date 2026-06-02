@extends('layouts.site', [
    'seoTitle' => 'L’archetier',
    'seoDescription' => 'Ivo Correia de Melo, archetier à Lyon, fabrique des archets contemporains en bois brésiliens alternatifs, dans une démarche durable et artisanale.',
])

@section('content')
    <x-site.hero
        eyebrow="Atelier"
        title="L’archetier"
        subtitle="Archets contemporains en bois brésiliens alternatifs, fabriqués à Lyon dans le respect de la tradition."
        variant="archetier"
    />

    <x-site.breadcrumb :items="[['label' => 'L’archetier']]" />

    <x-site.section container="readable">
        <div class="prose">
            <p>
                Je suis né et j’ai grandi à Recife, dans l’État de Pernambouc,
                la terre qui a donné son nom au bois de l’archet.
            </p>
            <p>
                Ce lien avec le Brésil nourrit naturellement mon travail :
                j’ai décidé de fabriquer des archets en explorant exclusivement des essences brésiliennes
                alternatives au pernambouc, choisies pour leurs qualités mécaniques,
                acoustiques et surtout en cohérence avec une démarche durable.
            </p>
        </div>
    </x-site.section>

    <x-site.section>
        <div class="split">
            <div class="prose">
                <h2>Une archeterie contemporaine</h2>
                <p>
                    Mon travail s’inspire de la tradition artisanale de l’archeterie française,
                    mais sans chercher à la cristalliser. Je m’intéresse certes à son esthétique, mais davantage à ce que cette tradition
                    représente aujourd’hui : exigence, fonctionnalité, musicalité.
                </p>
                <p>
                    L’archeterie contemporaine est pour moi un art qui est au service du jeu, tout d’abord.
                    C’est aussi l’art de la matière, mais qui doit avoir du sens, pour le musicien, pour la musique, et enfin pour la nature.
                </p>

                <h3>Bois brésiliens et matière vivante</h3>
                <p>
                    Le pernambouc reste la référence historique de l’archèterie.
                    Mais il n’est pas le seul bois capable de donner naissance
                    à un archet sérieux.
                </p>
                <p>
                    Je travaille exclusivement des essences brésiliennes, notamment le cumaru,
                    mais aussi l’ipé, le bois satiné, l’amarante, le wamara...
                    Je peux ainsi explorer toutes les possibilités que ces matières offrent : rigidité, densité, projection, harmoniques.
                    Ces matières sont véritablement capables de servir le jeu,
                    tout en participant à une approche plus durable.
                </p>
                <p>
                    <strong>Je ne propose pas une “alternative écologique au pernambouc”,
                        mais plutôt “une autre esthétique de jeu et de son”.</strong>
                </p>
            </div>
            <x-site.figure
                src="/assets/images/officina-hausse.jpeg"
                alt="Hausse d’archet en bois de cumaru"
            />
        </div>
    </x-site.section>

    <x-site.section variant="gradient">
        <div class="split">
            <div class="prose">
                <h2>Le travail à l’établi</h2>
                <p>
                    Chaque archet se construit par ajustements successifs :
                    choix du bois, façonnage de la baguette, cambrure,
                    équilibre, montage et essais.
                </p>
                <p>
                    Je définis le caractère d’un archet en travaillant le rapport entre
                    la densité, la rigidité, le point d’équilibre
                    et la sensation en main. Je les essaie personnellement.
                </p>
                <p>
                    Les mesures peuvent aider à comprendre une baguette,
                    mais elles ne remplacent jamais l’essai. Le critère final reste
                    ce que l’archet permet au musicien de faire - et tout peut être ajusté.
                </p>
            </div>
            <div class="prose">
                <h2>Le choix des matériaux</h2>
                <p>
                    Pour mes montages, je privilégie les matières naturelles et durables :
                    bois denses, os, fil de lin, acier inoxydable,
                    et autres métaux plus classiques comme l’argent, le maillechort ou le laiton.
                </p>
                <p>
                    Je n’utilise en revanche ni plastique ni carbone : ce choix relève
                    d’un principe de cohérence de matière, d’usage et d’identité sonore.
                </p>
            </div>
        </div>
    </x-site.section>

    <x-site.section>
        <div class="split split--reverse">
            <div class="prose">
                <h2>Incisi - Incidit</h2>
                <p>
                    J’ai nommé mon atelier Incidit en hommage à Ivo Incisi,
                    luthier italien ayant vécu au Brésil au début du XX<sup>e</sup> siècle.
                </p>
                <p>
                    Ce nom relie plusieurs choses qui comptent dans mon travail :
                    la tradition européenne de la facture instrumentale,
                    le Brésil, la matière, et l’idée qu’un atelier peut avancer
                    sans renier ses racines.
                </p>
            </div>
            <x-site.figure
                src="/assets/images/articles/incisi-almanach-1.png"
                alt="Ivo Incisi, luthier italien ayant vécu au Brésil au début du XXe siècle"
            />
        </div>
    </x-site.section>

    <x-site.section variant="surface" title="Découvrir les archets" heading-variant="accent">
        <div class="prose">
            <p>
                Les archets Ivo Incidit se répartissent en trois familles :
                Ars Antiqua, Ars Classica et Ars Nova. Elles ne correspondent pas
                à trois niveaux de gamme, mais à trois intentions différentes.
            </p>
            <p>
                <a class="btn btn--primary" href="{{ route('arcus.index') }}">Découvrir les archets</a>
            </p>
        </div>
    </x-site.section>
@endsection
