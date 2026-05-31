
  // Shrink header on scroll
  const header = document.querySelector('.site-header');

  window.addEventListener('scroll', function() {
    if (window.scrollY > 50) {
      header.classList.add('shrink');
    } else {
      header.classList.remove('shrink');
    }
  });

  // Toggle menu burger
  const burger = document.querySelector('.burger');
  const navMenu = document.querySelector('.nav-menu');

  burger.addEventListener('click', () => {
    navMenu.classList.toggle('active');
  });

/***
 * Initialise les carousels de citations.
 * Nécessite Fancyapps Carousel chargé avant site.js.
 */
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("[data-quote-carousel]").forEach((carousel) => {
    Carousel(
      carousel,
      {
        infinite: false,
        Arrows: {},
        Dots: {},
      },
      {
        Arrows,
        Dots,
      }
    ).init();
  });
});

/**
 * Initialise les showcases en carousel.
 * Nécessite Fancyapps Carousel chargé avant site.js.
 */
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll("[data-showcase-carousel]").forEach((carousel) => {
    Carousel(
      carousel,
      {
        infinite: false,
        center: false,
        fill: true,
        Arrows: {},
        Dots: {},
      },
      {
        Arrows,
        Dots,
      }
    ).init();
  });

  if (typeof Fancybox !== "undefined") {
    Fancybox.bind("[data-fancybox]", {});
  }
});