# Content Admin

Maracuja CMS sépare les pages codées des contenus vivants. Le client ne modifie pas la structure des pages, les CTA stratégiques ou les gabarits. Il gère seulement les contenus qui correspondent à une responsabilité claire.

## Règle de contenu

- Pages structurelles: Blade.
- Micro-contenus répétés ou variables: `Content Slots`.
- Contenus longs: `Articles`.
- Messages ponctuels: `Notices`.
- Actualités datées: `News`.
- Contenus métier: module dédié.

Le module `Pages` n’est pas un module client standard. Il peut rester disponible comme outil développeur, mais il ne doit pas devenir un page builder.

## Modules vivants

- `Content Slots`: petites valeurs nommées utilisées par les templates, comme un prix, une date ou un libellé court.
- `Notices`: annonce courte affichée sur la home si elle est publiee et dans sa période de visibilité.
- `News`: actualités publiées entre une date de début et une date de fin optionnelle.
- `Gallery`: images, textes courts, alt, credit, ordre et publication.

## Content Slots

Les `Content Slots` remplacent les anciens blocs libres rattachés aux pages. Ils permettent de modifier une petite valeur sans toucher à la page elle-même.

Un slot doit être défini avec le client quand une valeur change souvent ou régulièrement, ou quand elle relève vraiment de son autonomie éditoriale.

Exemples:

- `home.hero.cta_label`;
- `home.intro.title`;
- `services.essence.price`;
- `services.signature.price`.

Le template Blade décide où le slot s’affiche. Le client ne choisit pas la section, la mise en page ou le type de composant.

## Annonce courte

Le module `Annonce courte` sert aux messages ponctuels: horaires d’été, fermeture exceptionnelle, information temporaire.

Champs client:

- titre court optionnel;
- message court limité;
- lien optionnel;
- début et fin de publication;
- statut publié.

Si aucune annonce active n’existe, la section ne s’affiche pas.

## Actualités

Les actualités ont une fenêtre de publication:

- `published_at`: début de publication;
- `expires_at`: fin de publication;
- `is_published`: statut manuel.
- `is_pinned`: remonte l’actualité dans les listes.
- `has_detail_page`: active ou non une page détail publique.

Une actualité expirée est masquée sur la home, la liste publique et la page détail. Une actualité sans page détail reste visible en carte ou en liste, mais son URL détail retourne 404.

La durée proposée par défaut est configurée avec:

```env
MARACUJA_NEWS_DEFAULT_DURATION_DAYS=30
```

## Règle produit

Un contenu devient administrable quand il correspond à une responsabilité client claire. La mise en page, les titres structurants, les CTA et les variantes de composants restent dans le thème ou les templates Blade.
