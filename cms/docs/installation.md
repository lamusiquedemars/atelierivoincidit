# Installation

Maracuja CMS V1 est un starter Laravel + Filament. Chaque client a sa propre installation, sa base de donnees, ses medias et sa configuration.

## Nouvelle installation locale

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Admin:

- URL: `/admin`
- email demo: `admin@maracuja.test`
- mot de passe demo: `password`

## Configuration client minimale

Dans `.env`:

```env
APP_NAME="Nom du site"
APP_URL=https://example.com
APP_LOCALE=fr

MARACUJA_PRODUCT_NAME="Maracuja CMS"
MARACUJA_THEME=default
MARACUJA_OFFER=signature
MARACUJA_INDEXABLE=false
```

Ne passer `MARACUJA_INDEXABLE=true` qu au moment de mise en ligne publique.

## Profils d offre

```env
MARACUJA_OFFER=essence
MARACUJA_OFFER=signature
MARACUJA_OFFER=univers
```

Le profil fixe la base commerciale. Les variables `MARACUJA_MODULE_*` permettent d enlever un module pour un projet precis.

Exemple:

```env
MARACUJA_MODULE_CONTENT_SLOTS=true
MARACUJA_MODULE_NEWS=false
```

## Base de donnees

Le starter utilise SQLite en local par defaut. Pour un projet client livre, preferer MySQL/MariaDB si l hebergement est mutualise classique.

Exemple:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=client_site
DB_USERNAME=client_user
DB_PASSWORD=secret
```

## Livraison

Avant livraison:

```bash
php artisan maracuja:doctor
php artisan test
npm run build
php artisan view:cache
php artisan optimize:clear
```

Avant mise en ligne publique:

```bash
php artisan maracuja:doctor --production
```

Verifier ensuite:

- `/` repond en 200;
- `/admin/login` repond en 200;
- le formulaire contact fonctionne;
- les modules vendus apparaissent dans l admin;
- les modules non vendus sont absents;
- `MARACUJA_INDEXABLE=true` seulement si le site doit etre indexe;
- le compte demo a ete remplace ou supprime.

## Regle produit

Le client administre ses contenus vivants. Le developpeur garde les templates, les CTA structurants, la mise en page, les presets galerie et le theme.
