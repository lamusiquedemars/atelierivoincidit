# Migration Atelier Ivo Incidit vers Maracuja CMS

## Structure

- `current/`: site actuel PHP maison, conservé comme référence stable.
- `cms/`: nouvelle installation Laravel issue de Maracuja CMS Starter.
- `migration/`: notes, scripts et exports temporaires de migration.

## Regles

- Ne pas modifier `current/` sauf correction explicite du site actuel.
- Construire la nouvelle version dans `cms/`.
- Importer progressivement les données et médias depuis `current/`.
- Garder les URLs publiques importantes autant que possible.
- Ne pas ajouter le module Archets au starter générique : il appartient à cette implémentation Univers.

## Source des données

- Export SQL actuel: `current/storage/private/ivoin2573774.sql`
- Images d archets: `current/public/assets/images/archets/`
- Galerie atelier : `current/app/data/showcase.php`
- Pages référence : `current/app/pages/`

## Base de données

- La base MySQL existante reste la source de référence.
- La nouvelle application Laravel utilise la même base en local, avec le préfixe de tables `cms_`.
- Les anciennes tables comme `bow`, `photo`, `users`, `contact`, etc. ne doivent pas être écrasées.
- Les tables Laravel attendues seront donc `cms_users`, `cms_pages`, `cms_news_posts`, etc.
- Toute migration Laravel doit être lancée uniquement après vérification du préfixe `DB_PREFIX=cms_`.

## Notes à reprendre plus tard

- Élargir le modal Filament d’édition des archets : les selects sont trop étroits et les valeurs s’empilent.
- Corriger les thumbnails d’archets dans l’admin : ils ne s’affichent pas dans la liste ni dans le modal.
- Conserver comme option sérieuse la méthode actuelle des photos : créer un dossier `assets/images/archets/{code}` pour chaque archet est très rapide.
- Étudier plus tard les alternatives d’upload, sans imposer une interface glisser-déposer si elle ralentit le travail réel.

## Prochaine étape

Continuer la migration fidèle des pages publiques et du module métier Arcus dans `cms/`, sans modifier le site de référence dans `current/`.
