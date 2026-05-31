import { queryAll } from '../core/dom';

export function initFormStates(root = document) {
    queryAll('form[data-form]', root).forEach((form) => {
        if (form.dataset.formReady === 'true') {
            return;
        }

        form.dataset.formReady = 'true';

        form.addEventListener('submit', () => {
            const submitter = form.querySelector('[type="submit"]');

            form.classList.add('is-submitting');
            submitter?.setAttribute('aria-busy', 'true');
        });
    });
}
