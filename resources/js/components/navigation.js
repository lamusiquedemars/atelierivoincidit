import { queryAll } from '../core/dom';

export function initNavigation(root = document) {
    queryAll('[data-nav]', root).forEach((nav) => {
        const toggle = nav.querySelector('[data-nav-toggle]');
        const menu = nav.querySelector('[data-nav-menu]');

        if (!toggle || !menu || nav.dataset.navReady === 'true') {
            return;
        }

        nav.dataset.navReady = 'true';
        toggle.setAttribute('aria-expanded', 'false');

        toggle.addEventListener('click', () => {
            const isOpen = nav.classList.toggle('is-open');

            toggle.setAttribute('aria-expanded', String(isOpen));
            menu.hidden = !isOpen;
        });

        menu.hidden = window.matchMedia('(max-width: 760px)').matches;
    });
}
