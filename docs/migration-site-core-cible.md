# Atelier Ivo Incidit — cible Site Core et migration

**État : audit technique du 22 octobre 2026 — aucune donnée modifiée.**

## Décision de propriété

| Élément | Propriétaire cible | Rôle du site Atelier |
| --- | --- | --- |
| Pages, contenus courts, articles, annonces | Maracuja Site Core | édition publique |
| Médiathèque et galerie d'atelier | Maracuja Site Core | import et affichage public |
| Présentation des galeries | thème Atelier | preset `carousel`, visible mais non modifiable dans l'admin |
| Archets, caractéristiques, prix, disponibilité, photos par archet | Cremona, pack luthier / Archets | projection publique, sans double saisie |
| InciditVox | Atelier | outil spécialisé conservé hors Site Core |

## Constat vérifié

- PHP 8.4 et Laravel 13 sont déjà compatibles avec l'adoption du Core.
- Le thème actif déclaré localement est `atelier`; le rendu Gallery est
  `carousel`.
- La galerie éditoriale utilise les tables `galleries` et `gallery_images`.
- Le catalogue Arcus possède ses tables propres depuis la migration
  `2026_08_02_000000_create_native_arcus_tables`.
- `arcus_bow_media` référence déjà `media_assets` par `media_asset_id` : c'est
  une base saine pour préserver les relations pendant la bascule.
- Les photos historiques présentes dans `public/assets/images/archets` restent
  une source à importer de façon traçable ; elles ne constituent pas le
  stockage cible.

## Manifeste cible

```json
{
  "schema_version": 1,
  "site_key": "atelier-ivo-incidit",
  "presentation": {"theme": "atelier", "gallery_preset": "carousel"},
  "modules": {
    "site_settings": {"package": "site-core-foundation", "enabled": true},
    "pages": {"package": "site-core-foundation", "enabled": true},
    "content_slots": {"package": "site-core-foundation", "enabled": true},
    "notices": {"package": "site-core-foundation", "enabled": true},
    "media": {"package": "site-core-media", "enabled": true},
    "gallery": {"package": "site-core-gallery", "enabled": true},
    "articles": {"package": "site-core-publication", "enabled": true},
    "contact_form": {"package": "site-core-contact", "enabled": true},
    "arcus_projection": {"package": "site-core-cremona-connector", "enabled": false}
  }
}
```

`arcus_projection` reste désactivé jusqu'à ce que le catalogue Archets soit
réellement disponible dans Cremona. Le module Arcus local est une transition,
pas un module Site Core à extraire.

## Migration réversible proposée

1. Sauvegarder et restaurer à blanc la base Atelier et les médias avant toute
   modification.
2. Ajouter les packages Core via Composer et conserver les migrations locales
   historiques pendant la transition.
3. Faire un inventaire média, puis importer uniquement les fichiers canoniques
   dans `media/images`; conserver une table de correspondance chemin historique
   vers `media_asset_id`.
4. Basculer la galerie d'atelier sur Gallery Core et vérifier le rendu
   `carousel` sur mobile, lightbox, légendes et textes alternatifs.
5. Conserver les relations `arcus_bow_media` pendant la bascule Media : aucune
   photo d'archet n'est déplacée vers une galerie éditoriale.
6. Construire le pack Archets dans Cremona, importer les données Arcus puis
   valider une projection publique en lecture seule dans Atelier.
7. Seulement après comparaison fonctionnelle et validation des URLs : retirer
   les copies locales Media/Gallery et déprécier le catalogue Arcus local.

## Critères de sortie

- aucune URL publique historique d'image ne casse ;
- chaque photo de galerie et d'archet est retrouvable et traçable ;
- le site n'écrit plus de catalogue d'archets concurrent de Cremona ;
- une correction Media/Gallery vient exclusivement de Site Core ;
- rollback possible avant l'étape de nettoyage.
