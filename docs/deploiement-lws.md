# Déploiement LWS — Atelier Ivo Incidit

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
