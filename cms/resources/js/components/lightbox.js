import PhotoSwipeLightbox from 'photoswipe/lightbox';
import 'photoswipe/style.css';
import { queryAll } from '../core/dom';

export function initLightbox(root = document) {
    queryAll('[data-lightbox]', root).forEach((gallery) => {
        if (gallery.dataset.lightboxReady === 'true') {
            return;
        }

        gallery.dataset.lightboxReady = 'true';

        const lightbox = new PhotoSwipeLightbox({
            gallery,
            children: gallery.dataset.lightboxChildren || 'a',
            pswpModule: () => import('photoswipe'),
        });

        lightbox.init();
    });
}
