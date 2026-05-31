# Maracuja UI JS

Maracuja UI JS ajoute les interactions communes du starter sans framework front lourd. Le HTML reste Blade, le style reste Maracuja Front System, et JavaScript active des comportements via attributs `data-*`.

## Architecture

```txt
resources/js/
  app.js
  core/
    boot.js
    dom.js
  components/
    navigation.js
    disclosure.js
    reveal.js
    carousel.js
    lightbox.js
    form.js
  modules/
```

## Regle

Un module JS doit s'activer seulement si le HTML contient l'attribut attendu. Une page sans carousel ne charge pas de logique specifique cote template.

## Navigation

```html
<header class="site-header container" data-nav>
    <button class="btn btn--secondary nav-toggle" data-nav-toggle type="button">
        Menu
    </button>
    <nav class="site-nav" data-nav-menu>
        ...
    </nav>
</header>
```

## Disclosure

```html
<div class="disclosure" data-disclosure>
    <button class="disclosure__trigger" data-disclosure-trigger type="button">
        Question
    </button>
    <div class="disclosure__panel" data-disclosure-panel>
        Reponse.
    </div>
</div>
```

## Reveal au scroll

Le JS observe l'element, le CSS anime.

```html
<section data-reveal="fade-up" data-reveal-delay="150">
    ...
</section>
```

Variantes :

```txt
fade-up par defaut
fade
fade-left
scale
```

Le module respecte `prefers-reduced-motion`.

## Carousel

Le starter utilise Embla Carousel.

```html
<div class="carousel" data-carousel>
    <div class="carousel__viewport" data-carousel-viewport>
        <div class="carousel__track">
            <article class="carousel__slide">...</article>
            <article class="carousel__slide">...</article>
        </div>
    </div>
    <div class="carousel__controls">
        <button class="btn btn--secondary" data-carousel-prev type="button">Precedent</button>
        <button class="btn btn--secondary" data-carousel-next type="button">Suivant</button>
    </div>
</div>
```

Options HTML :

```txt
data-carousel-loop="false"
data-carousel-align="center"
```

## Lightbox

Le starter utilise PhotoSwipe pour les galeries.

```html
<div data-lightbox>
    <a href="/image-large.jpg" data-pswp-width="1600" data-pswp-height="1000">
        <img src="/image-thumb.jpg" alt="">
    </a>
</div>
```

## Form states

```html
<form data-form>
    ...
    <button type="submit">Envoyer</button>
</form>
```

Au submit, le formulaire recoit `is-submitting` et le bouton `aria-busy="true"`.
