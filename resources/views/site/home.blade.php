@extends('layouts.site', [
    'seoTitle' => $homePage?->seo_title,
    'seoDescription' => $homePage?->seo_description,
])

@section('content')
    @php($isAtelier = config('maracuja.theme') === 'atelier')

    @if ($isAtelier)
        <x-site.hero
            variant="home"
            eyebrow=""
            title="Atelier Ivo Incidit"
            subtitle="Archets contemporains fabriqués à Lyon, en bois brésiliens alternatifs."
            cta-url="{{ route('arcus.index') }}"
            cta-label="Découvrir les archets"
            secondary-cta-url="{{ route('atelier.probatio') }}"
            secondary-cta-label="Comprendre l’essai"
        />
    @else
        <x-site.hero
            variant="home"
            :eyebrow="config('maracuja.product_name')"
            :title="$homePage?->hero_title ?? $settings->site_name"
            :subtitle="$homePage?->hero_subtitle ?? $settings->baseline"
            :cta-url="$contactUrl"
            :cta-label="\App\Support\ContentSlots::value('home.hero.cta_label', 'Présenter un projet')"
            :secondary-cta-url="$servicesUrl"
            :secondary-cta-label="\App\Support\ContentSlots::value('home.hero.secondary_cta_label', 'Voir les services')"
        />
    @endif

    @if ($homeNotice)
        <div class="container notice-wrap">
            <x-site.notice :notice="$homeNotice" />
        </div>
    @endif

    @if ($isAtelier)
        <x-site.section variant="gradient">
            <div class="split">
                <div class="prose">
                    <p>
                        Je suis Ivo Correia de Melo, archetier à Lyon. J’ai nommé mon atelier
                        <em>Incidit</em> en hommage à Ivo Incisi, luthier italien passé par le Brésil
                        au début du XX<sup>e</sup> siècle.
                    </p>
                    <p>
                        Né à Recife, dans l’État de Pernambouc, au Brésil, je fabrique des archets
                        en explorant des bois brésiliens alternatifs au pernambouc. Mon travail s’inspire de la tradition française,
                        dans une logique d’évolution contemporaine.
                    </p>
                    <p>
                        Aussi violoniste, je conçois mes archets avant tout comme des outils de jeu, au service de la performance.
                    </p>
                </div>
                <x-site.figure
                    src="/assets/images/archets-colores.jpeg"
                    alt="Archets colorés de l’Atelier Ivo Incidit"
                />
            </div>
        </x-site.section>

        @if ($galleryImages->isNotEmpty())
            <x-site.section title="Galerie d’atelier" intro="Quelques archets réalisés récemment." heading-variant="decorated">
                <x-site.gallery
                    :images="$galleryImages"
                    layout="featured"
                    lightbox
                />
            </x-site.section>
        @endif

        <x-site.section title="Mes trois approches de l’archet" heading-variant="accent">
            <x-site.grid columns="3">
                <x-site.card title="Ars Antiqua" :url="route('arcus.range', 'ars-antiqua')" image="/assets/images/home-antiqua.HEIC" variant="featured">
                    Archets pour la musique ancienne, mais non seulement...
                </x-site.card>
                <x-site.card title="Ars Classica" :url="route('arcus.range', 'ars-classica')" image="/assets/images/home-classica.HEIC" variant="featured">
                    Archets classiques, formes et sensations classiques.
                </x-site.card>
                <x-site.card title="Ars Nova" :url="route('arcus.range', 'ars-nova')" image="/assets/images/home-nova.HEIC" variant="featured">
                    La nouvelle archèterie.
                </x-site.card>
            </x-site.grid>
        </x-site.section>

        <x-site.section variant="surface" title="Essayer avant de choisir" heading-variant="underline">
            <div class="prose">
                <p>
                    Un archet ne se juge pas seulement sur une fiche ou une photographie.
                    Il se comprend en main, avec un instrument, un geste, une manière de jouer.
                </p>
                <p>
                    Les archets disponibles peuvent être essayés à Lyon ou envoyés pour essai,
                    après un premier échange.
                </p>
                <p>
                    <a class="btn btn--primary" href="{{ route('atelier.probatio') }}">Comprendre l’essai</a>
                </p>
            </div>
        </x-site.section>

        <x-site.section>
            <div class="split split--reverse">
                <div class="prose">
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
                    <p>
                        <a class="btn btn--secondary" href="{{ route('atelier.officina') }}">Découvrir l’atelier</a>
                    </p>
                </div>
                <x-site.figure
                    src="/assets/images/tetes-archets.jpeg"
                    alt="Bois brésiliens utilisés pour les archets"
                />
            </div>
        </x-site.section>

        <x-site.section variant="gradient">
            <x-site.quote-carousel
                :items="$atelierQuotes"
                kicker="Quelques retours de musiciens"
                variant="editorial"
                items-per-view="1"
            />
        </x-site.section>
    @else
        <x-site.section
            :title="\App\Support\ContentSlots::value('home.intro.title', 'Le socle des offres Essence et Signature')"
            :intro="\App\Support\ContentSlots::value('home.intro.text', 'Un site vitrine administré, sans surcharge, avec les modules utiles au client et une base front propre.')"
            heading-variant="accent"
        >
            <x-site.grid columns="3">
                <x-site.feature-card title="Essence" icon="01" data-reveal>
                Un site vitrine clair, rapide à produire, avec pages structurées, contact et SEO de base.
                </x-site.feature-card>
                <x-site.feature-card title="Signature" icon="02" data-reveal data-reveal-delay="120">
                Une présence plus complète avec actualités, galerie, contenus plus riches et thème affirmé.
                </x-site.feature-card>
                <x-site.feature-card title="Univers" icon="03" data-reveal data-reveal-delay="240">
                Un module métier est ajouté seulement quand le client a un vrai besoin spécifique.
                </x-site.feature-card>
            </x-site.grid>
        </x-site.section>
    @endif

    @unless ($isAtelier)
    <x-site.section variant="muted" title="Une admin courte" intro="Le client voit ses contenus, pas un cockpit inutile." heading-variant="underline">
        <x-site.grid columns="2-3">
            <x-site.quote author="Maracuja CMS" meta="Principe produit">
                Moins d'options visibles, plus de structure derriere.
            </x-site.quote>

            <div class="stack stack--lg">
                <x-site.card title="Modules activables" kicker="Admin">
                    Pages, Actualités, Galerie, Contact et Paramètres s’affichent seulement si le projet en a besoin.
                </x-site.card>
                <x-site.card title="Pages cadrées" kicker="Front">
                    Le développeur garde la structure en Blade. Le client modifie uniquement les contenus prévus.
                </x-site.card>
            </div>
        </x-site.grid>
    </x-site.section>
    @endunless

    @if (! $isAtelier && $galleryImages->isNotEmpty())
        <x-site.section :title="$gallery?->title ?? \App\Support\ContentSlots::value('gallery.title', 'Galerie demo')" :intro="$gallery?->intro ?? \App\Support\ContentSlots::value('gallery.intro', 'Le Media System gere alt, legende, credit, dimensions et lightbox.')" heading-variant="decorated">
            <x-site.gallery
                :images="$galleryImages"
                :layout="config('maracuja.gallery.layout')"
                :lightbox="config('maracuja.gallery.lightbox')"
            />
        </x-site.section>
    @endif

    @if ($newsPosts->isNotEmpty())
        <x-site.section variant="surface" :title="$isAtelier ? 'Actualités' : 'Actualités démo'" :intro="$isAtelier ? null : 'Un module de contenu récurrent pour animer le site.'" heading-variant="accent">
            <x-site.grid columns="3">
                @foreach ($newsPosts as $post)
                    <x-site.card :title="$post->title" :url="$post->hasDetailPage() ? route('news.show', $post->slug) : null">
                        {{ $post->excerpt }}
                    </x-site.card>
                @endforeach
            </x-site.grid>
        </x-site.section>
    @endif

    @unless ($isAtelier)
    <x-site.section>
        <x-site.cta
            title="Prêt pour une démo client"
            text="Cette installation montre le socle Essence / Signature : contenu administrable, front system, media system et admin modulée."
            href="{{ route('contact') }}"
            label="Demander une démo"
            variant="brand"
            inline
        />
    </x-site.section>
    @endunless
@endsection
