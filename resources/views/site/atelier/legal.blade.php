@extends('layouts.site', [
    'seoTitle' => 'Mentions légales',
    'seoDescription' => 'Mentions légales du site Atelier Ivo Incidit.',
])

@section('content')
    <x-site.hero
        eyebrow="Légal"
        title="Mentions légales"
        subtitle="Transparence et responsabilité - Atelier Ivo Incidit"
        variant="page"
    />

    <x-site.breadcrumb :items="[['label' => 'Mentions légales']]" />

    <x-site.section container="readable">
        <div class="prose">
            <h2>1. Éditeur du site</h2>
            <p>
                <strong>Artisan :</strong> Ivo Correia de Melo<br>
                <strong>Atelier :</strong> Ivo Incidit<br>
                <strong>Forme juridique :</strong> Entreprise individuelle / micro-entrepreneur<br>
                <strong>Répertoire des Métiers :</strong> RM 894 976 133<br>
                <strong>Adresse :</strong> Collonges-au-Mont-d’Or, France<br>
                <strong>E-mail :</strong> <a href="mailto:info@atelierivoincidit.fr">info@atelierivoincidit.fr</a>
            </p>

            <h2>2. Hébergeur</h2>
            <p>
                Ce site est hébergé par :<br>
                <strong>Ligne Web Services (LWS)</strong><br>
                Adresse : 10 rue de Penthièvre, 75008 Paris, France.<br>
                SIREN / SIRET : 851 993 683.
            </p>

            <h2>3. Propriété intellectuelle</h2>
            <p>
                L’ensemble du contenu présent sur ce site, textes, photos, logo et mise en forme,
                est protégé par le droit de la propriété intellectuelle. Toute reproduction,
                distribution, adaptation ou réutilisation, partielle ou totale, est interdite
                sans mon accord écrit préalable.
            </p>

            <h2>4. Données personnelles</h2>
            <p>
                Lors des commandes ou des demandes d’essai d’archets, je collecte certaines données
                afin de gérer la relation client, l’expédition et les retours éventuels.
                Ces données sont utilisées uniquement à ces fins et ne sont pas transmises
                à des tiers non autorisés.
            </p>

            <h2>5. Responsabilité</h2>
            <p>
                Ce site a pour vocation de présenter mon travail d’artisan et mes créations d’archets.
                Je m’efforce de fournir des informations précises et à jour, mais je ne peux garantir
                l’absence d’erreurs ou d’omissions.
            </p>

            <h2>6. Liens externes</h2>
            <p>
                Le site peut contenir des liens vers d’autres sites. Je n’ai pas de contrôle sur leur
                contenu, leur disponibilité ou leurs pratiques de données.
            </p>

            <h2>7. Modification des mentions légales</h2>
            <p>
                Je me réserve le droit de modifier ces mentions légales à tout moment. La version
                en ligne actuelle prévaut sur toute version antérieure.
            </p>
        </div>
    </x-site.section>
@endsection
