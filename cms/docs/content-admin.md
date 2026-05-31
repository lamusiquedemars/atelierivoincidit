# Content Admin

Maracuja CMS separe les pages codees des contenus vivants. Le client ne modifie pas la structure des pages, les CTA strategiques ou les gabarits. Il gere seulement les modules prevus pour son offre.

## Modules vivants

- `Content Slots`: petites valeurs nommees utilisees par les templates, comme un prix, une date ou un libelle court.
- `Notices`: annonce courte affichee sur la home si elle est publiee et dans sa periode de visibilite.
- `News`: actualites publiees entre une date de debut et une date de fin optionnelle.
- `Gallery`: images, textes courts, alt, credit, ordre et publication.

## Content Slots

Les `Content Slots` remplacent les anciens blocs libres rattaches aux pages. Ils permettent de modifier une petite valeur sans toucher a la page elle-meme.

Exemples:

- `home.hero.cta_label`;
- `home.intro.title`;
- `services.essence.price`;
- `services.signature.price`.

Le template Blade decide ou le slot s affiche. Le client ne choisit pas la section, la mise en page ou le type de composant.

## Annonce courte

Le module `Annonce courte` sert aux messages ponctuels: horaires d ete, fermeture exceptionnelle, information temporaire.

Champs client:

- titre court optionnel;
- message court limite;
- lien optionnel;
- debut et fin de publication;
- statut publie.

Si aucune annonce active n existe, la section ne s affiche pas.

## Actualites

Les actualites ont une fenetre de publication:

- `published_at`: debut de publication;
- `expires_at`: fin de publication;
- `is_published`: statut manuel.
- `is_pinned`: remonte l actualite dans les listings.
- `has_detail_page`: active ou non une page detail publique.

Une actualite expiree est masquee sur la home, la liste publique et la page detail. Une actualite sans page detail reste visible en carte/listing, mais son URL detail retourne 404.

La duree proposee par defaut est configuree avec:

```env
MARACUJA_NEWS_DEFAULT_DURATION_DAYS=30
```

## Regle produit

Un contenu devient administrable quand il correspond a une responsabilite client claire. La mise en page, les titres structurants, les CTA et les variantes de composants restent dans le theme ou les templates Blade.
