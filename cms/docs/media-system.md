# Maracuja Media System

Le Media System encadre les images du starter : upload, metadonnees, accessibilite, performance et affichage public.

## Objectifs

- Eviter les images sans `alt`, sans dimensions ou sans convention de stockage.
- Rendre les galeries compatibles PhotoSwipe.
- Garder un rendu responsive simple en V1.
- Preparer plus tard les conversions WebP/AVIF et thumbnails.

## Stockage V1

```txt
storage/app/public/gallery
storage/app/public/news
storage/app/public/pages
storage/app/public/settings
```

Une installation doit executer :

```bash
php artisan storage:link
```

## Champs recommandes

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

Le module Galerie utilise deja cette structure.

## Regles alt

- Image informative : renseigner `alt_text`.
- Image decorative : `alt=""`.
- Si `alt_text` est vide dans Galerie, le starter utilise le titre de l'image comme fallback.

## Upload admin

Regles V1 :

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
    src="gallery/photo.webp"
    alt="Detail d'un archet"
    width="1200"
    height="800"
/>
```

Figure :

```blade
<x-site.figure
    src="gallery/photo.webp"
    alt="Detail d'un archet"
    caption="Detail de finition"
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

Le client gere seulement les images, textes, credits et ordre dans le module Galerie.

Le type de rendu est une decision de structure vendue avec le site :

```env
MARACUJA_GALLERY_LAYOUT=grid
```

Valeurs possibles :

```txt
grid      Galerie simple en grille.
featured  Portfolio avec premiere image mise en avant.
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

Le client ne choisit pas `grid`, `featured` ou `carousel` dans l'admin. Il administre les contenus du module Galerie.

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
```

Si les dimensions sont absentes, le composant utilise un fallback. En production, les dimensions doivent etre renseignees.

## Prochaines evolutions

- Lire automatiquement largeur/hauteur apres upload.
- Generer thumbnails.
- Generer WebP/AVIF.
- Ajouter un champ `is_decorative`.
- Ajouter un media picker reutilisable pour Pages, Actualites et modules metier.
