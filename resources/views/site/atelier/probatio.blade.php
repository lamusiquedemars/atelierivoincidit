@extends('layouts.site', [
    'seoTitle' => 'Essayer un archet à Lyon | Atelier Ivo Incidit',
    'seoDescription' => 'Essayez les archets Ivo Incidit à Lyon, sur rendez-vous chez Contempo Luthiers. Venez avec votre instrument et comparez plusieurs archets dans de vraies conditions de jeu.',
])

@push('styles')
    <style>
        .theme-ivo-incidit .trial-process {
            grid-template-columns: minmax(0, 2fr) minmax(220px, 1fr);
            align-items: center;
        }

        .theme-ivo-incidit .trial-process__visual,
        .theme-ivo-incidit .contempo-lyon__visual {
            width: min(100%, 380px);
            justify-self: end;
        }

        .theme-ivo-incidit .trial-process__visual img,
        .theme-ivo-incidit .contempo-lyon__visual img {
            aspect-ratio: 3 / 4;
            object-fit: cover;
        }

        @media (max-width: 800px) {
            .theme-ivo-incidit .trial-process,
            .theme-ivo-incidit .contempo-lyon {
                grid-template-columns: 1fr;
            }

            .theme-ivo-incidit .trial-process__visual,
            .theme-ivo-incidit .contempo-lyon__visual {
                justify-self: start;
            }
        }
    </style>
@endpush

@section('content')
    <x-site.hero
        eyebrow="Essai"
        title="Essayer un archet"
        subtitle="Choisir avec l’instrument en main."
        variant="essai"
    />

    <x-site.breadcrumb :items="[['label' => 'Essayer un archet']]" />

    <x-site.section container="readable">
        <div class="prose">
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
    </x-site.section>

    <x-site.section title="Ce que l’essai permet de vérifier" heading-variant="decorated">
        <div class="prose">
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
    </x-site.section>

    <x-site.section variant="surface" title="Comment se passe l’essai ?" heading-variant="accent">
        <div class="split trial-process">
            <div class="prose">
                <p>
                    Le plus simple est de commencer par un premier échange. Vous me dites quel instrument
                    vous jouez, votre pratique et ce que vous cherchez dans un archet.
                </p>
                <p>
                    Je peux préparer une petite sélection ; vous prenez ensuite le temps de jouer,
                    d’écouter et de comparer.
                </p>
            </div>
            <x-site.figure
                class="trial-process__visual"
                src="/assets/images/essai-main-archet.jpg"
                alt="Main tenant un archet au-dessus d’une partition"
                width="1200"
                height="1600"
            />
        </div>
    </x-site.section>

    <x-site.section title="Essayer à Lyon chez Contempo Luthiers" heading-variant="underline">
        <div class="split trial-process contempo-lyon">
            <div class="prose">
                <p>
                    À Lyon, je vous reçois sur rendez-vous chez Contempo Luthiers, l’atelier de Giovanni Corazzol,
                    maître luthier depuis près de trente ans.
                </p>
                <p>
                    C’est une grande chance pour moi d’y travailler ponctuellement et d’apprendre à ses côtés.
                    C’est dans ce cadre que je reçois les musiciens pour essayer mes archets avec leur propre
                    instrument, dans un lieu adapté à l’écoute et à la comparaison.
                </p>
                <p>
                    Venez avec votre instrument — violon, alto ou violoncelle — et, si possible, avec votre archet habituel.
                    Cela permet de comparer directement les sensations, la réponse, l’équilibre, l’articulation et le son.
                </p>
                <p>
                    Il n’est pas nécessaire de savoir à l’avance quel archet choisir. Je peux préparer une petite sélection
                    en fonction de votre instrument, de votre pratique et de ce que vous recherchez. L’essai se fait sans obligation d’achat.
                </p>
                <p>
                    <strong>Contempo Luthiers</strong><br>
                    9 quai Arloing<br>
                    69009 Lyon<br>
                    Essais Ivo Incidit sur rendez-vous.
                </p>
                <p>
                    <a class="btn btn--primary" href="{{ route('contact') }}#contact-form">Organiser un essai</a>
                </p>
                <p>
                    <a href="https://contempoluthiers.fr" target="_blank" rel="noopener noreferrer">Découvrir Contempo Luthiers</a>
                </p>
            </div>
            <x-site.figure
                class="contempo-lyon__visual"
                src="/assets/images/ivo-giovanni-contempo.jpg"
                alt="Ivo Correia de Melo et Giovanni Corazzol chez Contempo Luthiers à Lyon"
                width="1200"
                height="1600"
            />
        </div>
    </x-site.section>

    <x-site.section variant="surface" title="Essai à distance" heading-variant="accent">
        <div class="prose">
            <p>
                Si vous ne pouvez pas venir à Lyon, un essai par envoi peut être envisagé après un premier échange.
                Nous définissons alors simplement la sélection, la durée de l’essai, l’expédition et le retour.
            </p>
        </div>
    </x-site.section>

    <x-site.section variant="gradient" title="Si vous ne savez pas quel archet essayer" heading-variant="accent">
        <div class="prose">
            <p>
                Écrivez-moi simplement avec quelques informations :
                votre instrument, votre niveau, votre pratique, ce que vous aimez
                ou n’aimez pas dans votre archet actuel, et ce que vous recherchez.
            </p>
            <p>
                Je vous orienterai vers un ou deux archets possibles, sans vous demander
                de choisir seul à partir d’une fiche technique.
            </p>
            <p>
                Si vous avez déjà repéré un ou deux archets sur le site, indiquez simplement leur numéro dans votre message.
            </p>
            <p>
                <a class="btn btn--primary" href="{{ route('contact') }}#contact-form">Me demander conseil</a>
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
    </x-site.section>
@endsection
