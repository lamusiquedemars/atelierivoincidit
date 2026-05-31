import EmblaCarousel from 'embla-carousel';
import { queryAll } from '../core/dom';

export function initCarousel(root = document) {
    queryAll('[data-carousel]', root).forEach((carousel) => {
        if (carousel.dataset.carouselReady === 'true') {
            return;
        }

        const viewport = carousel.querySelector('[data-carousel-viewport]');
        const previous = carousel.querySelector('[data-carousel-prev]');
        const next = carousel.querySelector('[data-carousel-next]');

        if (!viewport) {
            return;
        }

        carousel.dataset.carouselReady = 'true';

        const loop = carousel.dataset.carouselLoop !== 'false';
        const align = carousel.dataset.carouselAlign || 'start';
        const embla = EmblaCarousel(viewport, { align, loop });

        previous?.addEventListener('click', () => embla.scrollPrev());
        next?.addEventListener('click', () => embla.scrollNext());
    });
}
