# Maracuja Media System

Le Media System encadre les images du starter : upload, métadonnées, accessibilité, performance et affichage public.

## Objectifs

- Éviter les images sans `alt`, sans dimensions ou sans convention de stockage.
- Rendre les galeries compatibles PhotoSwipe.
- Garder un rendu responsive simple en V1.
- Préparer plus tard les conversions WebP/AVIF et thumbnails.

## Stockage V1

```txt
storage/app/public/galleries/{gallery-slug}
storage/app/public/news
storage/app/public/pages
storage/app/public/articles
storage/app/public/articles/blocks
storage/app/public/site
```

Les uploads administrables utilisent explicitement le disk Laravel `public`.
Le chemin en base reste relatif au disk, par exemple
`galleries/atelier-home/photo.jpeg`.

Pour Atelier Ivo Incidit, les photos d’archets restent volontairement dans le
dossier métier historique :

```txt
public/assets/images/archets/{code}
```

Ce flux est rapide pour l'atelier et ne doit pas être mélangé avec les uploads
CMS génériques. Une future relation media du module Archets pourra reprendre ce
schéma sans obliger à changer les dossiers existants.

Une installation doit exécuter :

```bash
php artisan storage:link
```

## Champs recommandés

Pour une image administrable :

```txt
image_path
alt_text
caption
credit
width
height
position
is_published
```

Le module Galerie utilise déjà cette structure.

## Règles alt

- Image informative : renseigner `alt_text`.
- Image décorative : `alt=""`.
- Si `alt_text` est vide dans Galerie, le starter utilise le titre de l’image comme fallback.

## Upload admin

Règles V1 :

```txt
types: jpg, jpeg, png, webp
max: 5 MB
dossier: par module
```

## Composants Blade

```blade
<x-site.image />
<x-site.figure />
<x-site.gallery />
<x-site.lightbox-gallery />
```

Image :

```blade
<x-site.image
    src="galleries/atelier-home/photo.webp"
    alt="Détail d'un archet"
    width="1200"
    height="800"
/>
```

Figure :

```blade
<x-site.figure
    src="galleries/atelier-home/photo.webp"
    alt="Détail d'un archet"
    caption="Détail de finition"
    credit="Atelier Ivo Incidit"
    width="1200"
    height="800"
/>
```

Galerie avec lightbox :

```blade
<x-site.lightbox-gallery :images="$galleryImages" />
```

## Presets galerie vendus

Le client gère seulement les images, textes, crédits et ordre dans le module Galerie.

Le type de rendu est une décision de structure vendue avec le site :

```env
MARACUJA_GALLERY_LAYOUT=grid
```

Valeurs possibles :

```txt
grid      Galerie simple en grille.
featured  Portfolio avec première image mise en avant.
carousel  Carousel horizontal avec Embla.
```

Le template utilise :

```blade
<x-site.gallery
    :images="$galleryImages"
    :layout="config('maracuja.gallery.layout')"
    :lightbox="config('maracuja.gallery.lightbox')"
/>
```

Le client ne choisit pas `grid`, `featured` ou `carousel` dans l’admin. Il administre les contenus du module Galerie.

## Configuration

```env
MARACUJA_GALLERY_LAYOUT=featured
MARACUJA_GALLERY_LIGHTBOX=true
MARACUJA_GALLERY_TITLE="Galerie"
MARACUJA_GALLERY_INTRO="Quelques images du projet."
```

## PhotoSwipe

Pour fonctionner au mieux, chaque image de lightbox doit avoir :

```txt
data-pswp-width
data-pswp-height
data-pswp-caption
```

Si les dimensions sont absentes, le composant utilise un fallback. En production, les dimensions doivent être renseignées.
La légende de zoom est construite par le composant galerie depuis `caption` et `credit`.

## Prochaines évolutions

- Lire automatiquement largeur/hauteur après upload.
- Générer thumbnails.
- Générer WebP/AVIF.
- Ajouter un champ `is_decorative`.
- Ajouter un media picker réutilisable pour Pages, Actualités et modules métier.
