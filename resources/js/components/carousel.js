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
        const dots = carousel.querySelector('[data-carousel-dots]');

        if (!viewport) {
            return;
        }

        carousel.dataset.carouselReady = 'true';

        const loop = carousel.dataset.carouselLoop !== 'false';
        const align = carousel.dataset.carouselAlign || 'start';
        const embla = EmblaCarousel(viewport, { align, loop });

        previous?.addEventListener('click', () => embla.scrollPrev());
        next?.addEventListener('click', () => embla.scrollNext());

        const updateControls = () => {
            previous?.toggleAttribute('disabled', !embla.canScrollPrev());
            next?.toggleAttribute('disabled', !embla.canScrollNext());
        };

        if (dots) {
            const dotButtons = embla.scrollSnapList().map((_, index) => {
                const button = document.createElement('button');
                button.className = 'carousel__dot';
                button.type = 'button';
                button.setAttribute('aria-label', `Aller au slide ${index + 1}`);
                button.addEventListener('click', () => embla.scrollTo(index));
                dots.appendChild(button);

                return button;
            });

            const updateDots = () => {
                const selected = embla.selectedScrollSnap();

                dotButtons.forEach((button, index) => {
                    button.toggleAttribute('aria-current', index === selected);
                });
            };

            embla.on('select', updateDots);
            embla.on('reInit', updateDots);
            updateDots();
        }

        embla.on('select', updateControls);
        embla.on('reInit', updateControls);
        updateControls();
    });
}
