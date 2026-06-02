import { queryAll } from '../core/dom';

export function initDisclosure(root = document) {
    queryAll('[data-disclosure]', root).forEach((disclosure) => {
        const trigger = disclosure.querySelector('[data-disclosure-trigger]');
        const panel = disclosure.querySelector('[data-disclosure-panel]');

        if (!trigger || !panel || disclosure.dataset.disclosureReady === 'true') {
            return;
        }

        disclosure.dataset.disclosureReady = 'true';

        const startsOpen = disclosure.hasAttribute('data-disclosure-open');
        trigger.setAttribute('aria-expanded', String(startsOpen));
        panel.hidden = !startsOpen;

        trigger.addEventListener('click', () => {
            const isOpen = panel.hidden;

            panel.hidden = !isOpen;
            disclosure.classList.toggle('is-open', isOpen);
            trigger.setAttribute('aria-expanded', String(isOpen));
        });
    });
}
