# Blueprint Catalogue Métier

Un catalogue métier présente des objets, pièces, œuvres, instruments, lieux ou produits sans devenir une boutique générique.

L’objectif est de réutiliser un pattern, pas de créer un module `Products` universel.

## Principe

Chaque catalogue important doit avoir sa table métier.

Exemples:

- `bows` pour des archets;
- `instruments` pour des instruments;
- `garments` pour des vêtements;
- `works` pour des œuvres;
- `venues` pour des lieux;
- `rooms` pour des salles;
- `objects` pour des pièces artisanales.

On évite les taxonomies génériques incompréhensibles. Les champs doivent porter le vocabulaire réel du client.

## Pattern Réutilisable

Un module catalogue métier reprend généralement:

- une liste publique;
- une fiche détail publique;
- une image principale;
- une galerie ou une convention photo;
- un statut de visibilité;
- un statut métier, par exemple disponible, réservé, vendu, archivé;
- un prix optionnel ou une mention commerciale;
- des filtres utiles au domaine;
- des référentiels métiers;
- un ordre ou une mise en avant;
- des champs SEO;
- une ressource Filament dense mais lisible.

## Ce Qui Reste Spécifique

Chaque module garde ses vrais champs.

Pour des archets:

- gamme;
- instrument;
- bois;
- matériaux;
- mesures physiques;
- qualités de jeu.

Pour des vêtements:

- taille;
- coupe;
- matière;
- couleur;
- saison;
- disponibilité.

Pour des instruments:

- famille;
- modèle;
- année;
- état;
- dimensions;
- accessoires;
- sonorité ou usage.

## Admin

L’admin doit rester orienté métier:

- filtres courts et utiles;
- colonnes principales visibles;
- colonnes secondaires masquables;
- actions groupées seulement si elles aident vraiment;
- détail ou formulaire découpé en sections claires;
- pas de liste énorme dans une fiche si une liste filtrée fait mieux.

## Front

Le front doit être codé pour le domaine:

- route d’index;
- route détail;
- filtres si utiles;
- carte réutilisable;
- page détail adaptée aux champs réels;
- CSS de module dans `resources/css/modules`;
- thème client dans `resources/css/thèmes`.

## Photos

Deux options acceptables:

- dossier par item si le flux est rapide et stable;
- table média dédiée si l’on doit gérer ordre, légendes, texte alternatif, recadrage ou upload admin.

La méthode par dossier est valide quand elle sert le travail réel du client. Elle doit être documentée par convention.

## À Ne Pas Faire

- Ne pas créer un module `Products` configurable avec tous les champs possibles.
- Ne pas remplacer des champs métier par une taxonomie fourre-tout.
- Ne pas ajouter paiement, panier, stock, livraison ou TVA dans ce pattern.
- Ne pas promettre un e-commerce.

## Décision Produit

Quand un client demande un catalogue, Maracuja vend un module métier cadré.

Formulation commerciale:

> On crée un catalogue adapté à vos objets, avec les bons champs, les bons filtres et une fiche claire.

Ce module peut reprendre le pattern Arcus, mais son modèle, son vocabulaire et ses vues restent spécifiques au métier.
