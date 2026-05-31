# Maracuja Theme System

Le Theme System permet de changer l'ambiance d'un site sans changer sa structure Blade ni recreer les composants.

## Selection

Le theme actif se regle par configuration :

```php
'theme' => env('MARACUJA_THEME', 'default'),
```

Dans `.env` :

```env
MARACUJA_THEME=maracuja
```

Themes disponibles :

```txt
default
maracuja
atelier
```

Le layout ajoute automatiquement :

```html
<body class="site-shell theme-maracuja">
```

## Fichiers

```txt
resources/css/themes/default.css
resources/css/themes/maracuja.css
resources/css/themes/atelier.css
```

## Regle

Un theme doit surtout modifier des variables :

```css
.theme-maracuja {
    --color-bg: #fff8f4;
    --color-brand: #d96b4a;
    --color-brand-soft: #f4d8cf;
    --font-heading: var(--font-sans);
}
```

Un theme ne doit pas recreer du layout :

```css
/* Non */
.theme-atelier .section-archets {
    padding: 6rem 0;
    max-width: 1120px;
}

/* Oui */
.theme-atelier {
    --color-brand: #6c3e2a;
    --color-surface-muted: #e8d8b7;
}
```

## Role des themes

- `default` : theme neutre du starter.
- `maracuja` : theme agence, chaud, digital, fruit.
- `atelier` : theme generique artisan / atelier / matiere, inspire par Atelier Ivo sans etre specifique a lui.

Les modules metier restent dans `resources/css/modules`, pas dans `themes`.
