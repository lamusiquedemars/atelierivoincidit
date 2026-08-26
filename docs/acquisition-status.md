# État Acquisition — Atelier Ivo Incidit

> Référence de reprise. Aucun secret n’est stocké ici.

## Identité et responsabilités

| Élément | Valeur |
|---|---|
| Organisation / client | Atelier Ivo Incidit — Ivo Correia de Melo |
| Domaine public | `https://atelierivoincidit.fr` |
| Dépôt local | `/Users/ivocorreiademelo/Sites/atelierivoincidit` |
| Production / hébergement | LWS ; application Laravel dans `/htdocs` ; déploiement SFTP vers `/htdocs` ; terminal LWS disponible à l’utilisateur |
| Compte Google propriétaire | `ivo@maracujadigital.fr` |
| Accès Maracuja | Même identité pour cette marque propre |
| Compte Ads client | `200-507-3692 — Atelier Ivo Incidit` |
| MCC Maracuja | Rattachement accepté le 26 août 2026 |
| Pays / devise / fuseau | À conserver dans les paramètres du compte Ads ; ne pas modifier sans décision explicite |

## Décisions déjà prises

- Une seule identité Google est utilisée pour Atelier et Maracuja ; aucun second compte Google Ivo.
- GTM est le seul chargeur dans le site ; ne pas coller le snippet `gtag.js` GA4 directement.
- Consentement audience requis avant la mesure ; aucune interface de réouverture du choix n’a été ajoutée.
- Aucune campagne Google Ads ne doit être créée ou diffusée sans décision commerciale explicite.

## Étapes

| N° | Statut | Fait vérifiable / blocage | Prochaine action unique |
|---:|---|---|---|
| 0 | terminé | Objectif : visibilité, mesure et demandes de contact ; pas de campagne en cours. | — |
| 1 | terminé | Sitemap, robots, canonical HTTPS et URLs publiques contrôlés. | Surveiller l’indexation. |
| 2 | terminé | Propriété Domaine Search Console `atelierivoincidit.fr` validée ; sitemap soumis. | Attendre l’indexation. |
| 3 | terminé | Conteneur GTM `GTM-MC53SDPM`. | — |
| 4 | terminé | GTM et consentement déployés dans `resources/views/layouts/site.blade.php`. | — |
| 5 | terminé | Flux GA4 `G-P50G7P4N0D`. | — |
| 6 | terminé | `page_view` validé dans Tag Assistant et DebugView. | Attendre les rapports consolidés. |
| 7 | terminé | `generate_lead` émis seulement après succès serveur du formulaire. | — |
| 8 | terminé | `generate_lead` est un événement clé GA4. | — |
| 9 | terminé | Association Search Console ↔ GA4 créée. | Attendre les données Search Console dans GA4. |
| 10 | terminé | Compte Ads créé, relié à GA4 et rattaché au MCC Maracuja ; taggage automatique actif ; aucune campagne. | Vérifier le crédit/facturation seulement si nécessaire. |
| 11 | en attente | Conversion Ads depuis GA4 non importée. Une tentative de conversion Ads directe a été annulée et n’a laissé aucune action dans le récapitulatif. | Importer `generate_lead` depuis GA4 uniquement au moment de préparer une campagne. |
| 12 | non applicable | Aucune campagne approuvée. | Cadrage commercial avant tout lancement. |
| 13 | non applicable | Aucune campagne. | — |
| 14 | en attente | GA4 vient d’être installé ; rapports standard en cours de constitution. | Lire les premières données après 24–48 h. |

## Identifiants non secrets

- Search Console : `atelierivoincidit.fr` (propriété Domaine)
- GTM : `GTM-MC53SDPM`
- GA4 : `G-P50G7P4N0D`
- Google Ads : `200-507-3692`

## Journal succinct

- 2026-08-25 — GTM, consentement, GA4 et conversion formulaire validés ; SMTP de `info@atelierivoincidit.fr` corrigé.
- 2026-08-26 — Associations Search Console ↔ GA4 et GA4 ↔ Google Ads créées ; compte Ads rattaché au MCC Maracuja.
