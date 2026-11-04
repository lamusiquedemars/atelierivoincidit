# Déploiement cPanel — Atelier Ivo Incidit

L'hébergement de production, le déploiement applicatif, les tâches planifiées
et la base de données MySQL de ce site sont administrés dans **cPanel**, sur le
même compte serveur que Cremona. L'application est située dans :

```text
/home/cp2802197p33/apps/atelierivoincidit
```

L'accès opérationnel de référence est l'alias SSH local `maracuja-prod` déjà
configuré sur le Mac mini. Il utilise la clé générale autorisée du Mac ; aucun
mot de passe, profil SFTP historique ou nouvel accès Atelier ne doit être
recherché avant d'avoir contrôlé ce compte partagé.

## Accès et cible de déploiement

Avant une publication, contrôler la cible sans la modifier :

```bash
ssh maracuja-prod 'cd ~/apps/atelierivoincidit && pwd && php artisan --version'
```

Les anciens profils SFTP de `/Users/ivocorreiademelo/Sites/.vscode/sftp.json`
ne sont plus la procédure de référence pour Atelier : ils peuvent viser un
ancien accès ou un ancien chemin. Ne jamais déduire le chemin de production à
partir du nom de domaine FTP.

Le compte est partagé au niveau de l'hébergement, non au niveau des dossiers.
La protection repose donc sur la clé SSH autorisée et sur le contrôle explicite
du chemin ciblé avant chaque copie.

## Procédure

1. Vérifier le commit poussé et le chemin distant ci-dessus.
2. Copier uniquement les fichiers du commit concerné via `scp` sur
   `maracuja-prod`, sans toucher à `.env`, `vendor`, `storage` ni à la base
   MySQL.
3. Depuis `~/apps/atelierivoincidit`, exécuter uniquement les commandes Laravel
   nécessaires. Ne jamais lancer `migrate:fresh` ni une commande destructive.
4. Vérifier ensuite le résultat sur `https://atelierivoincidit.fr`.

## Base de données

- La base MySQL de production est gérée dans cPanel. Ses identifiants et son
  nom restent exclusivement dans le fichier `.env` de production.
- Consulter ou exporter les données via phpMyAdmin (ou les outils MySQL de
  cPanel) avant toute opération qui modifie le schéma ou des données.
- Les migrations Laravel sont exécutées depuis le terminal cPanel avec
  `php artisan migrate --force`, après sauvegarde vérifiée dans cPanel. Ne
  jamais utiliser `migrate:fresh`, `db:wipe` ou une importation SQL qui
  écraserait la base en production.

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

Programmer ensuite cette commande dans le planificateur cPanel, toutes les cinq
minutes :

```bash
cd ~/apps/atelierivoincidit && php artisan cremona:retry-deliveries --limit=50 >/dev/null 2>&1
```

Les trois variables `CREMONA_INCOMING_REQUESTS_URL`,
`CREMONA_INCOMING_REQUESTS_TOKEN` et `CREMONA_SITE_REFERENCE` restent dans le
`.env` de l’instance : elles ne sont jamais copiées ni versionnées.
