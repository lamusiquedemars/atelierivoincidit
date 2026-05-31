export function queryAll(selector, root = document) {
    return [...root.querySelectorAll(selector)];
}

export function on(element, event, handler, options = {}) {
    element.addEventListener(event, handler, options);

    return () => element.removeEventListener(event, handler, options);
}

export function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}
