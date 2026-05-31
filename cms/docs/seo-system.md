# Maracuja SEO System

Le SEO System V1 donne une base propre a chaque site livre : metas HTML, Open Graph, canonical, robots et sitemap.

## Idee simple

Le SEO n'est pas une magie. C'est surtout :

- dire clairement le titre de chaque page ;
- resumer la page en une description courte ;
- indiquer l'URL officielle avec `canonical` ;
- fournir une image de partage ;
- aider les moteurs avec un sitemap ;
- proteger les preproductions avec `noindex`.

## Indexation

Par defaut, le starter bloque l'indexation :

```env
MARACUJA_INDEXABLE=false
```

Pour un site en production :

```env
MARACUJA_INDEXABLE=true
```

Cela controle :

```html
<meta name="robots" content="noindex, nofollow">
```

ou :

```html
<meta name="robots" content="index, follow">
```

Et aussi `/robots.txt`.

## Champs globaux

Dans Parametres :

```txt
default_seo_title
default_seo_description
default_og_image_path
```

Ces valeurs servent de fallback si une page n'a pas ses propres champs.

## Champs par page

Pages et Actualites ont :

```txt
seo_title
seo_description
```

Les images utilisees pour Open Graph viennent de :

```txt
Page: hero_image_path
News: image_path
Fallback: default_og_image_path
```

## Metas generees

Le layout public genere :

```html
<title>
<meta name="description">
<meta name="robots">
<link rel="canonical">
<meta property="og:site_name">
<meta property="og:title">
<meta property="og:description">
<meta property="og:type">
<meta property="og:url">
<meta property="og:image">
<meta name="twitter:card">
```

## Sitemap

URL :

```txt
/sitemap.xml
```

Il liste :

- accueil ;
- pages publiees ;
- index actualites ;
- actualites publiees.

## Robots

URL :

```txt
/robots.txt
```

En preproduction :

```txt
User-agent: *
Disallow: /
```

En production :

```txt
User-agent: *
Allow: /
Sitemap: https://example.com/sitemap.xml
```

## Regles editoriales

Titre SEO :

```txt
50 a 60 caracteres idealement.
```

Description SEO :

```txt
140 a 160 caracteres idealement.
```

Une bonne description dit ce que la page offre, pas seulement ce qu'elle contient.
