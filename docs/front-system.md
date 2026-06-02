# Maracuja Front System

Maracuja Front System est le design system interne du starter. Il sert à produire des sites vitrines administrés sans repartir d’un gros fichier CSS par client.

## Objectif

- Garder une base commune entre les sites clients.
- Éviter les classes page-specific pour du layout générique.
- Laisser les thèmes porter l’identité visuelle.
- Garder les modules métier limités à leur propre comportement.

## Architecture CSS

```txt
resources/css/
  foundations/  reset, tokens, base, typography
  primitives/   container, section, grid, split, stack
  components/   button, card, hero, header, footer, form, gallery, table, showcase
  modules/      news, contact, gallery, puis modules client si nécessaire
  thèmes/       default, puis thèmes client
```

## Règles de nommage

Les primitives portent le layout réutilisable :

```html
<section class="section section--compact">
    <div class="container">
        ...
    </div>
</section>
```

Les composants portent une structure stable :

```html
<article class="card card--featured">
    <p class="card__kicker">Essence</p>
    <h3>Pages structurées</h3>
</article>
```

Les modules portent uniquement le comportement propre au domaine :

```html
<div class="arcus-list">
    ...
</div>
```

## À éviter

Ne pas créer une classe métier si elle ne fait que régler un padding, une largeur, une grille ou une couleur.

```css
/* Non */
.section-archets {
    padding: 0;
}

/* Oui */
.section--flush {
    padding-block: 0;
}
```

## Composants Blade disponibles

```blade
<x-site.hero />
<x-site.section />
<x-site.grid />
<x-site.card />
<x-site.button />
<x-site.heading />
<x-site.cta />
<x-site.feature-card />
<x-site.quote />
<x-site.showcase />
<x-site.badge />
```

Les pages doivent composer ces briques avant d'ajouter une classe spécifique.

## Variantes disponibles

Heros :

```txt
hero
hero--home
hero--page
hero--center
hero--split
hero--image
hero--overlay
hero--soft
hero--compact
hero--full
```

Sections :

```txt
section
section--compact
section--spacious
section--flush
section--muted
section--surface
section--brand
section--image
section--gradient
```

Headings :

```txt
heading
heading--plain
heading--accent
heading--underline
heading--decorated
heading--center
```

Boutons :

```txt
btn
btn--primary
btn--secondary
btn--ghost
btn--link
btn--danger
btn--small
btn--large
```

Cards :

```txt
card
card--featured
card--ghost
card--highlight
card--media
card--compact
card--horizontal
```

Layouts :

```txt
container
container--narrow
container--readable
container--wide
grid
grid--2
grid--3
grid--4
grid--2-3
grid--3-2
grid--auto
split
split--reverse
stack
cluster
cluster--center
cluster--between
cluster--end
```

Composants de contenu :

```txt
cta
cta--inline
cta--brand
feature-card
quote
quote-carousel
showcase
showcase--grid
showcase--featured
badge
badge--muted
price
table
table--simple
table--featured
```

## Exemples

Hero de page :

```blade
<x-site.hero
    title="Contact"
    subtitle="Une page simple, administrée et claire."
    variant="page"
/>
```

Hero image :

```blade
<x-site.hero
    title="Atelier"
    subtitle="Un univers visuel fort sans classe métier."
    variant="center"
    image="/assets/images/atelier.jpg"
/>
```

Section avec heading decoratif :

```blade
<x-site.section
    title="Une méthode claire"
    intro="Le client comprend, le développeur garde la structure."
    heading-variant="accent"
/>
```

Grille editorial 2/3 :

```blade
<x-site.grid columns="2-3">
    <div>Texte court</div>
    <div>Contenu principal</div>
</x-site.grid>
```

CTA :

```blade
<x-site.cta
    title="Prêt à lancer le site ?"
    text="On garde le socle propre et les contenus administrables."
    href="/contact"
    label="Démarrer"
    variant="brand"
    inline
/>
```

## Mapping depuis les sites existants

```txt
no-padding      -> section--flush
h2--moderne    -> heading--accent
h2--luxe       -> heading--decorated
h2--minimal    -> heading--underline
page-hero      -> hero hero--page
btn-primary    -> btn btn--primary
btn-secondary  -> btn btn--secondary
table--simple  -> table table--simple
```

Les classes `arcus-*`, `scripta-*`, `officina-*`, `essai-*`, `hero-ars-*` et équivalents restent hors du core. Elles iront dans un module ou thème client.

## Strategie theme

Un thème client surcharge les variables CSS, pas les primitives. Voir aussi `docs/theme-system.md`.

```css
:root {
    --color-bg: #fbfaf7;
    --color-brand: #2f6f5e;
    --font-heading: var(--font-sans);
}
```

Pour Atelier Ivo, les classes comme `arcus-*` resteront dans un module métier. Pour Maracuja Digital, les variantes de cartes, tables, sections et boutons doivent revenir dans les composants communs.
