import { initNavigation } from '../components/navigation';
import { initDisclosure } from '../components/disclosure';
import { initReveal } from '../components/reveal';
import { initCarousel } from '../components/carousel';
import { initLightbox } from '../components/lightbox';
import { initFormStates } from '../components/form';
import { initBackToTop } from '../components/back-to-top';

const initializers = [
    initNavigation,
    initDisclosure,
    initReveal,
    initCarousel,
    initLightbox,
    initFormStates,
    initBackToTop,
];

export function bootMaracujaUi(root = document) {
    initializers.forEach((init) => init(root));
}
