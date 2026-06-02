# Migration Atelier Ivo Incidit vers Maracuja CMS

## Structure

- `archive/`: ancien site PHP maison, conservé comme référence stable après migration.
- racine du dépôt : nouvelle installation Laravel issue de Maracuja CMS Starter.
- `migration/`: notes, scripts et exports temporaires de migration.

## Règles

- Ne pas modifier `archive/` sauf correction explicite du site actuel.
- La nouvelle version Laravel vit désormais à la racine du dépôt.
- importer progressivement les données et médias depuis `archive/`.
- Garder les URLs publiques importantes autant que possible.
- Ne pas ajouter le module Archets au starter générique : il appartient à cette implémentation Univers.

## Source des données

- Export SQL actuel: `archive/storage/private/ivoin2573774.sql`
- Images d’archets: `archive/public/assets/images/archets/`
- Galerie atelier : `archive/app/data/showcase.php`
- Pages référence : `archive/app/pages/`

## Base de données

- La base MySQL existante reste la source de référence.
- La nouvelle application Laravel utilise la même base en local, avec le préfixe de tables `cms_`.
- Les anciennes tables comme `bow`, `photo`, `users`, `contact`, etc. ne doivent pas être écrasées.
- Les tables Laravel attendues seront donc `cms_users`, `cms_pages`, `cms_news_posts`, etc.
- Toute migration Laravel doit être lancée uniquement après vérification du préfixe `DB_PREFIX=cms_`.

## Notes à reprendre plus tard

- Élargir le modal Filament d’édition des archets : les selects sont trop étroits et les valeurs s’empilent.
- Thumbnails d’archets dans l’admin : corrigé côté `cms`; la liste affiche l’image principale et le détail montre les chemins détectés.
- Photos d’archets : méthode conservée à court terme avec dossier `assets/images/archets/{code}` et convention à formaliser.
- Étudier plus tard les alternatives d’upload, sans imposer une interface glisser-déposer si elle ralentit le travail réel.
- Réorganiser le CSS Ivo Incidit après stabilisation : garder le principe `base.css` + thème, avec des commentaires de sections comme dans le site `current`, et découper si nécessaire les surcharges par composants.
- Formaliser la différence `module installé` / `module activé` dans Maracuja CMS : un module non vendu ne doit pas seulement être masqué par config, il doit être absent de l’installation client ou impossible à activer sans intervention Maracuja. Appliquer ensuite ce pattern à Galerie, News, Articles, Contact et Arcus.

## Prochaine étape

La migration fidèle est maintenant remontée à la racine du dépôt. Le dossier `archive/` reste une référence figée : il peut contenir d’anciens noms ou routes tant qu’ils servent à comparer l’existant.
