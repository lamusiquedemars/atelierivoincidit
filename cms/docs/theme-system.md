# Maracuja Theme System

Le Theme System permet de changer l'ambiance d'un site sans changer sa structure Blade ni recréer les composants.

## Selection

Le thème actif se règle par configuration :

```php
'theme' => env('MARACUJA_THEME', 'default'),
```

Dans `.env` :

```env
MARACUJA_THEME=maracuja
```

Thèmes disponibles :

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
resources/css/thèmes/default.css
resources/css/thèmes/maracuja.css
resources/css/thèmes/atelier.css
```

## Regle

Un thème doit surtout modifier des variables :

```css
.theme-maracuja {
    --color-bg: #fff8f4;
    --color-brand: #d96b4a;
    --color-brand-soft: #f4d8cf;
    --font-heading: var(--font-sans);
}
```

Un thème ne doit pas recréer du layout :

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

## Role des thèmes

- `default` : thème neutre du starter.
- `maracuja` : thème agence, chaud, digital, fruit.
- `atelier` : thème generique artisan / atelier / matière, inspiré par Atelier Ivo sans être spécifique à lui.

Les modules métier restent dans `resources/css/modules`, pas dans `thèmes`.
