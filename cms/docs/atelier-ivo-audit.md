# Audit Atelier Ivo Incidit

Date: 2026-05-26

## Verdict offre

Atelier Ivo Incidit correspond à l’offre **Univers**.

Ce n’est pas une Signature simple, car le site ne se limite pas à des pages, une galerie et quelques actualités. Il contient un vrai module métier : catalogue d’archets, gammes, fiches détaillées, mesures physiques, qualités de jeu, matériaux, statuts, prix et photos par archet.

Ce n’est pas non plus du sur-mesure illimité: le besoin est cadrable en un module métier propre, réutilisable pour d’autres artisans, catalogues ou ateliers.

## État actuel

Architecture actuelle:

- site PHP maison avec routeur simple;
- pages dans `app/pages`;
- composants dans `app/components`;
- configuration dans `config`;
- assets publics dans `public/assets`;
- admin PHP maison dans `app/admin`;
- base MySQL exportée dans `storage/private/ivoin2573774.sql`;
- images d’archets dans `public/assets/images/archets/{code}`.

Pages publiques principales:

- accueil;
- archets;
- gammes: Ars Antiqua, Ars Classica, Ars Nova;
- détail archet: `/arcus/{code}`;
- essai;
- archetier;
- contact;
- mentions légales;
- CGV;
- articles partiellement présents.

## Données métier

Tables identifiées dans l’export SQL:

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

Champs importants côté archet:

- code;
- nom;
- instrument;
- style;
- forme;
- bois;
- couleur;
- matériaux du bouton, de la hausse, de la coulisse, de la pointe;
- poids, longueur, équilibre, densité, vitesse, élasticité, fréquence, amortissement;
- qualités de jeu : flexibilité, réactivité, maniabilité, pression naturelle, timbre, projection, sustain, articulation;
- notes;
- trait court;
- gamme;
- statut;
- prix;
- remise;
- visible/non visible.

## Médias

Inventaire rapide:

- environ 47 dossiers d’archets;
- environ 157 fichiers image dans `public/assets/images/archets`;
- environ 14 documents PDF/DOC/pages/numbers dans `public/assets/docs`;
- galerie d’atelier dans `app/data/showcase.php`;
- images de contenu dans `public/assets/images`.

Mapping Maracuja CMS:

- images de galerie globale -> module Gallery;
- images par archet -> relation media propre dans le module Archets;
- documents techniques -> stockage public documenté ou module Documents plus tard.

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

Le starter doit reprendre l’intention visuelle via `resources/css/thèmes/atelier.css`, mais pas recopier la logique CSS fichier par fichier.

Mapping attendu:

- layout/header/footer -> layout Maracuja CMS;
- hero -> composant `x-site.hero`;
- cards/gammes -> `FeatureGrid` ou composant Blade dédié;
- galerie atelier -> preset gallery `carousel`;
- pages archets -> module Laravel dédié.

## JS

JS actuel:

- shrink header;
- burger menu;
- Fancyapps Carousel pour citations;
- Fancyapps Carousel pour showcase;
- Fancybox pour lightbox.

Mapping Maracuja CMS:

- navigation -> déjà couvert;
- carousel -> déjà couvert via Embla;
- lightbox -> déjà couvert via PhotoSwipe;
- reveal/back-to-top -> déjà couverts.

## Mapping Maracuja CMS

Modules existants réutilisables:

- Site Settings: identité, SEO, contact, réseaux;
- Pages: pages codées et protégées;
- Content Slots: petits textes/mentions modifiables;
- Gallery: galerie atelier;
- News: articles si conservé;
- Contact: formulaire;
- Theme: `atelier`.

Module à créer:

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
- `/arcus/{range}/{code}` ou garder `/arcus/{code}` si préservation SEO;
- page détail archet;
- éventuellement filtres par instrument.

Admin Filament:

- Archets;
- Gammes;
- Référentiels: instruments, bois, matériaux, qualités;
- Photos d’archets;
- statuts visible/disponible/vendu.

## Points à préserver

- URLs existantes autant que possible;
- textes principaux déjà travaillés;
- structure des gammes;
- photos par code d’archet;
- statut/prix/remise;
- SEO des pages importantes;
- philosophie: catalogue sans paiement.

## Points à éviter

- migrer tel quel le CSS fichier par fichier;
- refaire un admin spécial en PHP;
- transformer les gammes en page builder;
- mettre les fiches archets dans le module Pages;
- confondre galerie globale et photos d’un archet.

## Plan de migration recommandé

1. Profil `univers` confirmé dans le starter.
2. Créer le module `Archets` dans Maracuja CMS.
3. Reproduire les tables utiles en migrations Laravel.
4. Importer les référentiels depuis l’export SQL.
5. Importer les archets et leurs photos.
6. Créer les vues publiques : index, gamme, détail.
7. Adapter le thème `atelier`.
8. Vérifier les URLs et redirections.
9. Comparer visuellement l’actuel et la version Laravel.
10. Taguer le starter en `v0.2.0-lab` après migration réussie.

## Estimation

Migration technique sérieuse mais cadrable.

Pour obtenir une première version Laravel fonctionnelle :

- module Archets + migrations: 0.5 à 1 jour;
- import SQL/images: 0.5 jour;
- vues publiques index/gamme/détail: 1 jour;
- admin Filament propre: 0.5 à 1 jour;
- theme/polish/verification: 1 jour.

Total réaliste avec Codex: 3 à 4 jours de travail itératif, ou 1 à 2 jours pour une première version labo non définitive.
