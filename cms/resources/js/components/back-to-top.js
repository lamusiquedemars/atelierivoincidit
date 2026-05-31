export function initBackToTop(root = document) {
    const button = root.querySelector('[data-back-to-top]');

    if (!button) {
        return;
    }

    const threshold = Number(button.dataset.backToTopThreshold || 480);

    const updateVisibility = () => {
        button.hidden = window.scrollY < threshold;
    };

    button.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
        });
    });

    updateVisibility();
    window.addEventListener('scroll', updateVisibility, { passive: true });
}
