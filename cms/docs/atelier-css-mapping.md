# Cartographie CSS — Atelier Ivo Incidit

But : définir pour chaque fichier CSS du thème Atelier et de l’installation Ivo Incidit quelles propriétés peuvent être modifiées depuis le CSS client (`ivo-incidit.css`) et lesquelles doivent rester dans le thème/template.

## Règles générales
- `Interdit` : ne pas modifier depuis `ivo-incidit.css`.
- `Préférer variables` : modifier uniquement via variables thématiques.
- `Autorisé` : override ciblé via `ivo-incidit.css` sur des propriétés d’apparence client.

### Attributs autorisés en CSS client
- couleurs d’accent, couleurs de fond de section, couleurs de texte de marque
- tailles de titres, hiérarchie typographique, contrastes de texte via variables
- espacements de section, padding de composant, gap des blocs
- rayons de boutons/cartes/badges
- styles de bordures, ombres, dégradés de composants
- styles de boutons, CTA, cartes et blocs d’appel
- alignements et ajustements client spécifiques

### Attributs interdits ou à éviter
- `html`, `body`, reset global, `box-sizing`, `font-size` global, line-height global
- tokens de `resources/css/foundations/tokens.css`
- structure des primitives (`container`, `grid`, `stack`, `split`, `section`, `cluster`)
- refonte de la structure d’un composant partagé
- modifications directes de `resources/css/themes/atelier.css`, `default.css`, `maracuja.css`

## Tableau par fichier CSS

| Fichier | Couche | Attributs modifiables depuis `ivo-incidit.css` | Recommandation |
|---|---|---|---|
| `resources/css/foundations/tokens.css` | Foundation | Aucun | Interdit, conserver les tokens partagés |
| `resources/css/foundations/base.css` | Foundation | Aucun | Interdit, ne pas remonter la couche du thème |
| `resources/css/foundations/reset.css` | Foundation | Aucun | Interdit, reset commun |
| `resources/css/foundations/typography.css` | Foundation | Variables typographiques (taille, line-height, famille via thèmes) | Préférer variables de thème |
| `resources/css/app.css` | Application | Variables d’application, wrappers de page | Autorisé avec parcimonie |
| `resources/css/primitives/container.css` | Primitive | max-width, gap, padding via variables | Préférer variables, pas de réécriture structurelle |
| `resources/css/primitives/grid.css` | Primitive | gap, alignement, colonnes via variables | Préférer variables |
| `resources/css/primitives/stack.css` | Primitive | gap, espacement vertical via variables | Préférer variables |
| `resources/css/primitives/split.css` | Primitive | ratio, gap via variables | Préférer variables |
| `resources/css/primitives/section.css` | Primitive | padding/espacement de section | Préférer variables |
| `resources/css/primitives/cluster.css` | Primitive | gap, alignement via variables | Préférer variables |
| `resources/css/components/back-to-top.css` | Composant | couleur, position, icône | Autorisé |
| `resources/css/components/badge.css` | Composant | couleurs, rayon, typographie | Autorisé |
| `resources/css/components/breadcrumb.css` | Composant | couleur, espacement, typographie | Autorisé |
| `resources/css/components/button.css` | Composant | couleur, fond, bordure, rayon, padding, typographie | Autorisé via variables |
| `resources/css/components/card.css` | Composant | fond, ombre, rayon, padding | Autorisé |
| `resources/css/components/carousel.css` | Composant | couleur des contrôles, arrière-plan, légende | Autorisé |
| `resources/css/components/cta.css` | Composant | couleurs, typographie, bordure, espacement | Autorisé |
| `resources/css/components/disclosure.css` | Composant | couleur summary, icône, marge | Autorisé |
| `resources/css/components/feature-card.css` | Composant | accents, typographie, fond | Autorisé |
| `resources/css/components/footer.css` | Composant | couleurs, espacement, alignement, liens | Autorisé |
| `resources/css/components/form.css` | Composant | bordure, focus, couleur champ, rayon, bouton | Préférer variables, éviter de casser la grille de formulaire |
| `resources/css/components/gallery.css` | Composant | overlay, légende, espacement, couleur | Autorisé |
| `resources/css/components/header.css` | Composant | branding, hauteur, bouton CTA, espacement nav | Autorisé |
| `resources/css/components/heading.css` | Composant | taille/espacement des titres, décorations | Autorisé via variables |
| `resources/css/components/hero.css` | Composant | background-image, overlay, espacement, texte | Autorisé |
| `resources/css/components/media.css` | Composant | légende, ratio, bordure, fond | Autorisé |
| `resources/css/components/notice.css` | Composant | couleurs, icônes, bordure, espacement | Autorisé |
| `resources/css/components/price.css` | Composant | accents prix, badges, typographie | Autorisé |
| `resources/css/components/prose.css` | Composant | liens, citations, listes, marges via variables | Préférer variables |
| `resources/css/components/quote.css` | Composant | bordure, couleur de citation, espacement | Autorisé |
| `resources/css/components/reveal.css` | Composant | couleur de titre, icône, marge | Autorisé |
| `resources/css/components/showcase.css` | Composant | image, alignement, accent, espacement | Autorisé |
| `resources/css/components/table.css` | Composant | bordure, fond de ligne, hover, texte | Autorisé |

## Modules

| Fichier | Couche | Attributs modifiables depuis `ivo-incidit.css` | Recommandation |
|---|---|---|---|
| `resources/css/modules/articles.css` | Module | titres, teasers, séparateurs, espacement, couleur de fond | Autorisé |
| `resources/css/modules/contact.css` | Module | blocs contact, boutons, labels, espacement | Autorisé |
| `resources/css/modules/gallery.css` | Module | overlay, légende, espacement, structure visuelle | Autorisé |
| `resources/css/modules/news.css` | Module | accents news, cartes, titres, séparateurs | Autorisé |

## Thèmes

| Fichier | Couche | Attributs modifiables depuis `ivo-incidit.css` | Recommandation |
|---|---|---|---|
| `resources/css/themes/atelier.css` | Thème template | Aucun direct | Interdit pour Ivo Incidit |
| `resources/css/themes/default.css` | Thème template | Aucun direct | Interdit |
| `resources/css/themes/maracuja.css` | Thème template | Aucun direct | Interdit |
| `resources/css/themes/ivo-incidit.css` | Thème client | couleurs de marque, hiérarchie typographique, espacements, overrides ciblés de composants | Autorisé, point d’entrée client |

## Notes
- `ivo-incidit.css` est le point d’override client autorisé, mais il doit rester ciblé : on change l’apparence, pas la structure CSS partagée.
- Si un changement est important ou crée un nouveau comportement, il doit être proposé dans `resources/css/components/` ou `resources/css/modules/` plutôt que dans le fichier client.
- `current/public/assets/css/*.css` sert de référence historique uniquement et n’est pas à modifier directement.

## Exemple de bonnes pratiques
- changer les couleurs de boutons depuis `ivo-incidit.css` en adaptant les variables de `button.css`
- ajuster les tailles de titres via des variables de thème plutôt que des sélecteurs globaux `h1,h2`
- modifier l’apparence d’une page Contact via `resources/css/modules/contact.css` ou un override ciblé sur `.contact-section`

---

Cette cartographie est complète pour les fichiers existants dans `resources/css`. Si tu veux, je peux transformer ce tableau en CSV ou en version condensée dans `docs/atelier-autonomie.md` section 5.1.
