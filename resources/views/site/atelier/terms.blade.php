@extends('layouts.site', [
    'seoTitle' => 'Conditions Générales de Vente',
    'seoDescription' => 'Conditions Générales de Vente de l’atelier Ivo Incidit.',
])

@section('content')
    <x-site.hero
        eyebrow="Vente"
        title="Conditions Générales de Vente"
        subtitle="Transparence, exigence et savoir-faire artisanal."
        variant="page"
    />

    <x-site.breadcrumb :items="[['label' => 'Conditions Générales de Vente']]" />

    <x-site.section container="readable">
        <div class="prose">
            <h2>1. Informations sur l’atelier</h2>
            <p>
                <strong>Artisan :</strong> Ivo Correia de Melo<br>
                <strong>Atelier :</strong> Ivo Incidit<br>
                <strong>Entreprise individuelle enregistrée au Répertoire des Métiers :</strong>
                nº 894 976 133 RM 69<br>
                <strong>Lieu :</strong> 30 chemin de l’Écully 69660 Collonges-au-Mont-d’Or<br>
                <strong>E-mail :</strong> <a href="mailto:info@atelierivoincidit.fr">info@atelierivoincidit.fr</a>
            </p>

            <h2>2. Objet</h2>
            <p>
                Les présentes Conditions Générales de Vente régissent les ventes d’archets neufs
                réalisés artisanalement par l’atelier Ivo Incidit, ainsi que les modalités d’essai,
                d’expédition, de garantie et de paiement.
            </p>

            <h2>3. Prix et paiement</h2>
            <p>Les prix sont indiqués en euros TTC pour les ventes en France. Le paiement peut être effectué :</p>
            <ul>
                <li>en espèces ;</li>
                <li>par virement bancaire ;</li>
                <li>par chèque, en 1 à 4 fois, selon accord préalable.</li>
            </ul>
            <p>
                Pour les commandes sur mesure, un acompte d’environ un tiers du prix peut être demandé.
                Le solde est dû à la livraison, après validation et satisfaction du client. En cas d’annulation,
                l’acompte peut être remboursé déduction faite d’un forfait de frais de dossier de 100 €.
            </p>

            <h2>4. Délais de fabrication</h2>
            <p>
                Les délais de fabrication sont de 1 à 2 semaines selon le modèle et la disponibilité des
                matériaux. Une estimation plus précise est communiquée au moment de la commande.
            </p>

            <h2>5. Essais des archets</h2>
            <p>Tous les archets peuvent être envoyés pour un essai à domicile. Les modalités sont les suivantes :</p>
            <ul>
                <li>Durée d’essai : 14 jours à réception.</li>
                <li>Si l’archet n’est pas retenu, les frais d’envoi et de retour sont à la charge du client.</li>
                <li>Si l’archet est acheté, l’envoi initial est offert.</li>
                <li>Des modèles de démonstration non destinés à la vente peuvent être proposés comme base de discussion pour une commande personnalisée.</li>
            </ul>

            <h2>6. Expédition</h2>
            <p>
                Les expéditions sont réalisées via Colissimo suivi. L’atelier n’est pas responsable
                des retards imputables au transporteur.
            </p>

            <h2>7. Garanties</h2>
            <h3>7.1 Garantie légale de conformité</h3>
            <p>
                Conformément aux articles L.217-3 à L.217-20 du Code de la consommation,
                les archets bénéficient d’une garantie légale de conformité de 2 ans.
            </p>

            <h3>7.2 Garantie commerciale artisanale</h3>
            <p>
                En complément de la garantie légale, l’atelier offre une garantie artisanale d’un an,
                couvrant la baguette, la hausse, la visserie, la garniture et une révision complète
                au bout d’un an.
            </p>

            <h3>7.3 Exclusions de garantie</h3>
            <p>La garantie commerciale ne couvre pas :</p>
            <ul>
                <li>l’usure normale des crins ;</li>
                <li>les dommages dus à un choc ou une chute ;</li>
                <li>l’exposition à la chaleur excessive, l’humidité ou un stockage inadapté ;</li>
                <li>une tension excessive ou un usage non conforme.</li>
            </ul>

            <h2>8. Retours</h2>
            <p>
                En cas de retour dans le cadre d’un essai non concluant ou d’une garantie,
                le client est invité à contacter l’atelier pour organiser l’envoi.
            </p>

            <h2>9. Données personnelles</h2>
            <p>
                Les données fournies par le client sont utilisées uniquement pour la gestion des commandes
                et ne sont jamais transmises à des tiers.
            </p>

            <h2>10. Litiges</h2>
            <p>
                En cas de litige, une solution amiable sera recherchée en priorité. À défaut d’accord,
                les tribunaux compétents seront ceux du ressort de Lyon.
            </p>
        </div>
    </x-site.section>
@endsection
