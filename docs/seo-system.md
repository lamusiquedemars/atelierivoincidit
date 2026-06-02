# Maracuja SEO System

Le SEO System V1 donne une base propre à chaque site livré : métas HTML, Open Graph, canonical, robots et sitemap.

## Idée simple

Le SEO n’est pas une magie. C'est surtout :

- dire clairement le titre de chaque page ;
- résumer la page en une description courte ;
- indiquer l’URL officielle avec `canonical` ;
- fournir une image de partage ;
- aider les moteurs avec un sitemap ;
- protéger les préproductions avec `noindex`.

## Indexation

Par défaut, le starter bloque l'indexation :

```env
MARACUJA_INDEXABLE=false
```

Pour un site en production :

```env
MARACUJA_INDEXABLE=true
```

Cela contrôle :

```html
<meta name="robots" content="noindex, nofollow">
```

ou :

```html
<meta name="robots" content="index, follow">
```

Et aussi `/robots.txt`.

## Champs globaux

Dans Paramètres :

```txt
default_seo_title
default_seo_description
default_og_image_path
```

Ces valeurs servent de fallback si une page n’a pas ses propres champs.

## Champs par page

Pages et Actualités ont :

```txt
seo_title
seo_description
```

Les images utilisées pour Open Graph viennent de :

```txt
Page: hero_image_path
News: image_path
Fallback: default_og_image_path
```

## Metas générées

Le layout public génère :

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
- pages publiées ;
- index actualités ;
- actualités publiées.

## Robots

URL :

```txt
/robots.txt
```

En préproduction :

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

## Règles éditoriales

Titre SEO :

```txt
50 à 60 caractères idéalement.
```

Description SEO :

```txt
140 à 160 caractères idéalement.
```

Une bonne description dit ce que la page offre, pas seulement ce qu’elle contient.
