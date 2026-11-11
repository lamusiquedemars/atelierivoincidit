# Déploiement LWS — Atelier Ivo Incidit

> **QUARANTAINE — ne pas exécuter ce guide.** La référence d'accès décrite est
> locale à un poste et le transfert manuel n'est pas une procédure partagée et
> validée. N'ouvre pas de fichier SFTP local cité ici et ne reprends pas ses
> destinations ou commandes. Voir le
> [registre des documents retirés/en quarantaine](../../maracuja-control/docs/documents-a-retirer.md)
> et la [politique commune](../../maracuja-control/docs/deploiement-multi-sites.md).

## Accès SFTP

La configuration de référence est l’entrée **`LWS Atelier`** du fichier local
`/Users/ivocorreiademelo/Sites/.vscode/sftp.json`. Elle cible le répertoire
LWS `/htdocs/`.

Le fichier `.vscode/sftp.json` du dépôt est une copie locale ignorée par Git :
il doit rester synchronisé depuis cette configuration de référence. Ne jamais
afficher, versionner ou recopier le mot de passe dans une documentation ou une
commande.

## Procédure

1. Contrôler le répertoire distant avant l’envoi.
2. Envoyer uniquement les fichiers du commit concerné, sans toucher à `.env`,
   `vendor`, `storage` ni à la base MySQL.
3. Depuis le terminal LWS, dans `~/htdocs`, exécuter les commandes Laravel
   nécessaires. Ne jamais lancer `migrate:fresh` ni une commande destructive.
4. Vérifier ensuite le résultat sur `https://atelierivoincidit.fr`.

Pour un changement de vues ou de configuration sans migration de schéma :

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Pont Cremona

Le pont conserve chaque envoi dans `cremona_deliveries`, puis tente l’envoi
immédiatement afin que le formulaire ne dépende pas d’un worker permanent. Une
erreur temporaire reste `pending` et peut être rejouée sans doublon grâce à la
clé d’idempotence.

Après le premier déploiement du pont, exécuter une seule fois :

```bash
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
```

Programmer ensuite dans le planificateur LWS, toutes les cinq minutes :

```bash
cd ~/htdocs && php artisan cremona:retry-deliveries --limit=50 >/dev/null 2>&1
```

Les trois variables `CREMONA_INCOMING_REQUESTS_URL`,
`CREMONA_INCOMING_REQUESTS_TOKEN` et `CREMONA_SITE_REFERENCE` restent dans le
`.env` de l’instance : elles ne sont jamais envoyées par SFTP ni versionnées.
