# Atelier Ivo Incidit - Guide d’autonomie

Ce document sert à avancer manuellement pendant la migration Atelier vers Maracuja CMS.

## 1. Pourquoi les fichiers sont dispersés ?

Dans l’ancien site PHP, beaucoup de choses étaient regroupées ici :

```text
current/app/pages/
```

Une page contenait souvent à la fois :

- l’URL implicite ;
- le contenu ;
- le HTML ;
- parfois de la logique de base de données ;
- parfois une décision de design.

Dans Laravel / Maracuja CMS, on sépare par responsabilité.

```text
routes/web.php
```

Déclare les URL publiques.

```text
app/Http/Controllers/
```

Prépare les données et choisit la vue.

```text
resources/views/
```

Contient le HTML Blade.

```text
app/Modules/
```

Contient les modules métier ou éditoriaux.

```text
app/Filament/Resources/
```

Contient l’admin.

```text
app/Support/
```

Contient des aides sans état : données de support, renderers, helpers, petits services.

## 2. Pourquoi `app/Support` ?

`app/Support` sert aux classes utiles qui ne sont pas vraiment :

- un contrôleur ;
- un modèle Eloquent ;
- une ressource admin ;
- une vue ;
- un module métier complet.

Exemples actuels :

```text
app/Support/AtelierHomeContent.php
```

Contient les données statiques de la home Atelier : galerie d’atelier et témoignages. Ce n’est pas une table BDD, pas un contrôleur, pas une vue. C’est donc du support.

```text
app/Support/ArticleBlocks.php
```

Transforme les blocs structurés d’un article en HTML front. Ce n’est pas propre à Atelier, c’est un renderer générique du CMS.

Plus tard, si `AtelierHomeContent` grossit trop, on pourra le déplacer vers :

```text
app/Modules/Atelier/Support/AtelierHomeContent.php
```

Mais pour l’instant, `app/Support` est acceptable.

## 3. Carte rapide des pages Atelier

Home :

```text
resources/views/site/home.blade.php
app/Support/AtelierHomeContent.php
```

Archetier :

```text
resources/views/site/atelier/officina.blade.php
```

Essayer un archet :

```text
resources/views/site/atelier/probatio.blade.php
```

Contact :

```text
resources/views/site/contact.blade.php
```

Mentions légales :

```text
resources/views/site/atelier/legal.blade.php
```

CGV :

```text
resources/views/site/atelier/terms.blade.php
```

Archets :

```text
app/Modules/Arcus/
resources/views/site/arcus/
app/Filament/Resources/Arcus/Bows/
```

Articles :

```text
app/Modules/Articles/
resources/views/site/articles/
app/Filament/Resources/Articles/
app/Support/ArticleBlocks.php
```

## 4. Le chemin mental

Quand tu cherches d’où vient une page :

```text
URL -> route -> controller -> vue Blade -> données éventuelles
```

Exemple `/archetier` :

```text
routes/web.php
app/Http/Controllers/AtelierPageController.php
resources/views/site/atelier/officina.blade.php
```

Exemple `/arcus/c025` :

```text
routes/web.php
app/Modules/Arcus/Http/Controllers/ArcusController.php
app/Modules/Arcus/Support/ArcusCatalog.php
resources/views/site/arcus/show.blade.php
base MySQL existante + photos dans current/public/assets/images/archets/c025/
```

## 5. À faire - priorité haute

### 5.1 Adapter le CSS Atelier

Objectif : que le site CMS retrouve davantage le rendu du site actuel, mais sans revenir au gros CSS fourre-tout.

Point produit important :

- le CSS actuel d’Ivo Incidit ne doit pas devenir automatiquement le CSS modèle du thème Atelier ;
- `atelier.css` doit représenter un thème template réutilisable pour des artisans, ateliers, créateurs, métiers d’art ;
- `atelier.css` ne doit pas être modifié pour répondre aux goûts d’Ivo Incidit ;
- Ivo Incidit doit avoir son propre thème client, avec des modifications légères et cadrées ;
- une personnalisation client ne doit pas forcément créer un nouveau thème complet.

Thèmes par défaut Maracuja CMS :

```text
default
atelier
maracuja
```

Thème client Atelier Ivo Incidit :

```text
ivo-incidit
```

Fichiers à regarder :

```text
resources/css/thèmes/atelier.css
resources/css/thèmes/ivo-incidit.css
resources/css/components/
resources/css/modules/
resources/css/app.css
```

Règle :

- si c’est général, modifier un composant ;
- si c’est propre au template Atelier réutilisable, modifier `thèmes/atelier.css` ;
- si c’est propre à Ivo Incidit, modifier uniquement le thème client, par exemple `thèmes/ivo-incidit.css` ;
- si c’est propre à un module, créer ou modifier `resources/css/modules/xxx.css`.

La bonne cible industrielle :

```text
CSS de base Maracuja
-> composants communs
-> thème template : default / atelier / maracuja
-> thème client léger : ivo-incidit, client-x, client-y
```

La personnalisation client devrait idéalement être pilotée par des paramètres :

- polices ;
- couleurs principales ;
- couleurs d’accent ;
- largeur de contenu ;
- rayons des boutons/cartes ;
- quelques tailles typographiques ;
- quelques espacements ;
- style des titres ;
- style des boutons ;
- niveau d’ornement.

À terme, ces paramètres pourraient devenir un formulaire interne de cadrage ou une config par client, plutôt qu’un CSS libre écrit au hasard.

Après modification CSS :

```bash
npm run build
```

### 5.2 Articles sur le site

La config Atelier est dans :

```text
.env
```

Décision validée : le libellé public définitif est `Articles`.

La config Atelier doit rester :

```env
MARACUJA_ARTICLES_PUBLIC_LABEL=Articles
MARACUJA_ARTICLES_PUBLIC_PATH=articles
```

Puis :

```bash
/Applications/MAMP/bin/php/php8.4.1/bin/php artisan optimize:clear
```

À décider : garder une redirection de `/scripta` vers `/articles` si l’URL a déjà été partagée.

### 5.3 Remettre les textes du site actuel

Source de vérité :

```text
current/app/pages/
```

Pages déjà migrées mais à relire :

```text
resources/views/site/home.blade.php
resources/views/site/atelier/officina.blade.php
resources/views/site/atelier/probatio.blade.php
resources/views/site/contact.blade.php
resources/views/site/atelier/legal.blade.php
resources/views/site/atelier/terms.blade.php
```

Objectif : remplacer mes formulations ajoutées quand elles ne sont pas souhaitées par les textes exacts du site actuel.

Méthode :

1. Ouvrir l’ancien fichier dans `current/app/pages/`.
2. Ouvrir le fichier Blade équivalent.
3. Copier le texte uniquement.
4. Garder la structure Blade propre.
5. Lancer :

```bash
/Applications/MAMP/bin/php/php8.4.1/bin/php artisan view:clear
```

## 6. Galerie de la home

Actuellement, la galerie de la home Atelier est statique dans :

```text
app/Support/AtelierHomeContent.php
resources/views/site/home.blade.php
```

Problème identifié :

- les photos ne défilent pas ;
- elles ne s’agrandissent pas ;
- elles ne sont pas administrables.

Décision à prendre :

### Option A - galerie codée mais interactive

On garde les images dans `AtelierHomeContent.php`, mais on rend la galerie avec le composant existant :

```text
resources/views/components/site/gallery.blade.php
resources/views/components/site/lightbox-gallery.blade.php
resources/js/components/lightbox.js
resources/js/components/carousel.js
```

Avantage : rapide, propre, pas d’admin.

### Option B - galerie administrable

On utilise le module Galerie existant :

```text
admin/gallery-images
app/Modules/Gallery/
resources/views/components/site/gallery.blade.php
```

Le client peut gérer les images, mais pas choisir le layout.

Pour Atelier, il faudra alors filtrer une galerie de placement `home` ou créer une notion de groupe/collection. Ce n’est pas encore fait.

Recommandation : commencer par Option A, puis basculer en Option B si tu veux vraiment que la home soit administrable.

## 7. Formulaire de contact

Fichier :

```text
resources/views/site/contact.blade.php
```

Contrôleur :

```text
app/Http/Controllers/ContactController.php
```

Modèle :

```text
app/Modules/Contact/Models/ContactSubmission.php
```

Tu veux seulement :

- email ;
- message.

État actuel : fait.

Le formulaire visible ne demande plus que `email` et `message`. La table conserve encore techniquement un champ `name`, rempli automatiquement avec l’email pour rester compatible avec la structure actuelle.

Si tu modifies encore l’email reçu, vérifier :

```text
resources/views/mail/contact-submission-received.blade.php
```

Point produit : pas besoin de module personnalisable en admin. C’est au cadrage projet.

## 8. Eyebrow : d’où ça vient ?

L’eyebrow est le petit texte au-dessus du H1.

Composant :

```text
resources/views/components/site/hero.blade.php
```

Exemple d’appel :

```blade
<x-site.hero
    eyebrow="Atelier"
    title="L’archetier"
    subtitle="..."
/>
```

Si tu veux changer le texte, tu modifies la valeur `eyebrow`.

Si tu veux l’enlever sur une page :

```blade
<x-site.hero
    :eyebrow="null"
    title="Titre"
/>
```

Mais attention : le composant actuel remet `Maracuja CMS` par défaut. Il faudra modifier le composant pour respecter `null`.

À faire dans :

```text
resources/views/components/site/hero.blade.php
```

Remplacer :

```blade
<p class="eyebrow">{{ $eyebrow ?? 'Maracuja CMS' }}</p>
```

Par :

```blade
@if (filled($eyebrow))
    <p class="eyebrow">{{ $eyebrow }}</p>
@endif
```

Et changer la prop par défaut :

```blade
'eyebrow' => null,
```

Ensuite tu peux choisir page par page.

## 9. Autres points à prévoir

### Admin archets

À faire plus tard :

- modal trop étroit ;
- selects illisibles ;
- thumbnails corrigés dans la liste admin ;
- chemins publics affichés dans le détail admin ;
- workflow photo par dossier conservé à court terme, convention à documenter.

### Navigation

Décision actuelle : garder `Articles` comme libellé public. `Actualités` reste un module générique désactivable selon le besoin Atelier.

### Home

La home est encore un peu hybride :

```text
resources/views/site/home.blade.php
```

Elle contient à la fois le comportement starter et le comportement Atelier.

Refactor possible :

```text
resources/views/site/home.blade.php
resources/views/site/atelier/home.blade.php
```

Puis dans :

```text
app/Http/Controllers/HomeController.php
```

choisir la vue selon le thème.

### Textes SEO

À vérifier page par page :

- title ;
- description ;
- H1 ;
- cohérence du vocabulaire.

## 10. Commandes de sécurité

Depuis :

```bash
cd /Users/ivocorreiademelo/Sites/atelierivoincidit/cms
```

Après Blade/PHP :

```bash
/Applications/MAMP/bin/php/php8.4.1/bin/php artisan view:clear
/Applications/MAMP/bin/php/php8.4.1/bin/php artisan test
```

Après CSS/JS :

```bash
npm run build
```

Check global :

```bash
/Applications/MAMP/bin/php/php8.4.1/bin/php artisan maracuja:doctor
```

## 11. État de reprise

Fait :

1. Libellé public des articles fixé sur `Articles`.
2. Formulaire contact simplifié et demandes entrantes suivies côté CMS.
3. Thumbnails et chemins des photos d’archets visibles dans l’admin.
4. Arbitrages éditoriaux appliqués, ancien tableau de validation supprimé.

Reste à faire :

1. Relire les pages publiques migrées dans le navigateur.
2. Vérifier si le composant hero doit encore gérer l’eyebrow page par page.
3. Adapter ou confirmer la galerie home interactive.
4. Faire une passe CSS Atelier après validation des pages.
5. Noter les incohérences restantes pour reprise avec Codex.

## 12. Règle finale

Si c’est une structure de page : Blade.

Si c’est une donnée longue éditoriale : Articles.

Si c’est une donnée métier archet : Arcus.

Si c’est une image d’archet : source historique dans `current/public/assets/images/archets/{code}`, affichage CMS dans `public/assets/images/archets/{code}` pour l’instant.

Si c’est du design global : CSS component ou thème.

Si tu hésites, ne crée pas un module. Note le besoin et attends la reprise.
