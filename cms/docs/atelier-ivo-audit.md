# Audit Atelier Ivo Incidit

Date: 2026-05-26

## Verdict offre

Atelier Ivo Incidit correspond a l offre **Univers**.

Ce n est pas une Signature simple, car le site ne se limite pas a des pages, une galerie et quelques actualites. Il contient un vrai module metier: catalogue d archets, gammes, fiches detaillees, mesures physiques, qualites de jeu, materiaux, statuts, prix et photos par archet.

Ce n est pas non plus du sur-mesure illimite: le besoin est cadrable en un module metier propre, reutilisable pour d autres artisans, catalogues ou ateliers.

## Etat actuel

Architecture actuelle:

- site PHP maison avec routeur simple;
- pages dans `app/pages`;
- composants dans `app/components`;
- configuration dans `config`;
- assets publics dans `public/assets`;
- admin PHP maison dans `app/admin`;
- base MySQL exportee dans `storage/private/ivoin2573774.sql`;
- images d archets dans `public/assets/images/archets/{code}`.

Pages publiques principales:

- accueil;
- archets;
- gammes: Ars Antiqua, Ars Classica, Ars Nova;
- detail archet: `/arcus/{code}`;
- essai;
- archetier;
- contact;
- mentions legales;
- CGV;
- articles/scripta partiellement present.

## Donnees metier

Tables identifiees dans l export SQL:

- `bow`;
- `range`;
- `instrument`;
- `style`;
- `shape`;
- `wood`;
- `color`;
- `material`;
- `origin`;
- `garnish`;
- `size`;
- `quality`;
- `quality_ranges`;
- `photo`;
- `article`;
- `tag`;
- `article_tag`;
- `contact`;
- `users`;
- `login_attempts`.

Le module central est `bow`.

Champs importants cote archet:

- code;
- nom;
- instrument;
- style;
- forme;
- bois;
- couleur;
- materiaux du bouton, de la hausse, de la coulisse, de la pointe;
- poids, longueur, equilibre, densite, vitesse, elasticite, frequence, amortissement;
- qualites de jeu: flexibilite, reactivite, maniabilite, pression naturelle, timbre, projection, sustain, articulation;
- notes;
- trait court;
- gamme;
- statut;
- prix;
- remise;
- visible/non visible.

## Medias

Inventaire rapide:

- environ 47 dossiers d archets;
- environ 157 fichiers image dans `public/assets/images/archets`;
- environ 14 documents PDF/DOC/pages/numbers dans `public/assets/docs`;
- galerie d atelier dans `app/data/showcase.php`;
- images de contenu dans `public/assets/images`.

Mapping Maracuja CMS:

- images de galerie globale -> module Gallery;
- images par archet -> relation media propre dans le module Archets;
- documents techniques -> stockage public documente ou module Documents plus tard.

## Front et CSS

CSS actuel:

- `base.css`;
- `theme.css`;
- `header.css`;
- `arcus-list.css`;
- `arcus-detail.css`;
- `article.css`;
- `contact.css`;
- `scripta.css`;
- `inciditvox.css`;
- `admin.css`.

Le starter doit reprendre l intention visuelle via `resources/css/themes/atelier.css`, mais pas recopier la logique CSS fichier par fichier.

Mapping attendu:

- layout/header/footer -> layout Maracuja CMS;
- hero -> composant `x-site.hero`;
- cards/gammes -> `FeatureGrid` ou composant Blade dedie;
- galerie atelier -> preset gallery `carousel`;
- pages archets -> module Laravel dedie.

## JS

JS actuel:

- shrink header;
- burger menu;
- Fancyapps Carousel pour citations;
- Fancyapps Carousel pour showcase;
- Fancybox pour lightbox.

Mapping Maracuja CMS:

- navigation -> deja couvert;
- carousel -> deja couvert via Embla;
- lightbox -> deja couvert via PhotoSwipe;
- reveal/back-to-top -> deja couverts.

## Mapping Maracuja CMS

Modules existants reutilisables:

- Site Settings: identite, SEO, contact, reseaux;
- Pages: pages codees et protegees;
- Content Slots: petits textes/mentions modifiables;
- Gallery: galerie atelier;
- News: articles/scripta si conserve;
- Contact: formulaire;
- Theme: `atelier`.

Module a creer:

- `Archets`.

Sous-modules/tables probables:

- `Bow`;
- `BowRange`;
- `Instrument`;
- `BowStyle`;
- `BowShape`;
- `Wood`;
- `Material`;
- `Origin`;
- `Garnish`;
- `BowSize`;
- `Quality`;
- `BowPhoto`.

Pages publiques Laravel:

- `/arcus`;
- `/arcus/{range}`;
- `/arcus/{range}/{code}` ou garder `/arcus/{code}` si preservation SEO;
- page detail archet;
- eventuellement filtres par instrument.

Admin Filament:

- Archets;
- Gammes;
- Referentiels: instruments, bois, materiaux, qualites;
- Photos d archets;
- statuts visible/disponible/vendu.

## Points a preserver

- URLs existantes autant que possible;
- textes principaux deja travailles;
- structure des gammes;
- photos par code d archet;
- statut/prix/remise;
- SEO des pages importantes;
- philosophie: catalogue sans paiement.

## Points a eviter

- migrer tel quel le CSS fichier par fichier;
- refaire un admin special en PHP;
- transformer les gammes en page builder;
- mettre les fiches archets dans le module Pages;
- confondre galerie globale et photos d un archet.

## Plan de migration recommande

1. Renommer le profil `custom` en `univers` dans le starter.
2. Creer le module `Archets` dans Maracuja CMS.
3. Reproduire les tables utiles en migrations Laravel.
4. Importer les referentiels depuis l export SQL.
5. Importer les archets et leurs photos.
6. Creer les vues publiques: index, gamme, detail.
7. Adapter le theme `atelier`.
8. Verifier les URLs et redirections.
9. Comparer visuellement l actuel et la version Laravel.
10. Taguer le starter en `v0.2.0-lab` apres migration reussie.

## Estimation

Migration technique serieuse mais cadrable.

Pour obtenir une premiere version Laravel fonctionnelle:

- module Archets + migrations: 0.5 a 1 jour;
- import SQL/images: 0.5 jour;
- vues publiques index/gamme/detail: 1 jour;
- admin Filament propre: 0.5 a 1 jour;
- theme/polish/verification: 1 jour.

Total realiste avec Codex: 3 a 4 jours de travail iteratif, ou 1 a 2 jours pour une premiere version labo non definitive.
