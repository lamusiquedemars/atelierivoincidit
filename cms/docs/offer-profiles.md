# Offer Profiles

Maracuja CMS peut etre configure par profil commercial. Le profil donne une base coherente pour l offre vendue, puis les modules restent des interrupteurs fins par projet.

## Profils

```env
MARACUJA_OFFER=essence
MARACUJA_OFFER=signature
MARACUJA_OFFER=univers
```

## Essence

Profil minimal pour site vitrine simple:

- parametres du site;
- pages structurees;
- contact.

Modules desactives par defaut:

- annonce courte;
- contenus courts;
- actualites;
- galerie.

## Signature

Profil vitrine plus complet:

- parametres du site;
- annonce courte;
- contenus courts;
- pages structurees;
- actualites;
- galerie;
- contact.

## Univers

Profil cadré pour les sites avec module metier simple, catalogue, reservation ou outil connecte.

## Interrupteurs par module

Un module doit etre autorise par le profil et active par sa variable pour etre visible.

Exemple: vendre une Signature sans galerie.

```env
MARACUJA_OFFER=signature
MARACUJA_MODULE_GALLERY=false
```

Le layout public, les routes protegees et les ressources Filament doivent passer par `App\Support\Modules::enabled()`.
