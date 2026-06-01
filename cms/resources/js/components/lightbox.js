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

        lightbox.on('uiRegister', () => {
            lightbox.pswp.ui.registerElement({
                name: 'caption',
                order: 9,
                isButton: false,
                appendTo: 'root',
                html: '',
                onInit: (element, pswp) => {
                    const updateCaption = () => {
                        const trigger = pswp.currSlide?.data?.element;
                        const caption = trigger?.dataset?.pswpCaption || '';

                        element.textContent = caption;
                        element.hidden = caption === '';
                    };

                    pswp.on('change', updateCaption);
                    pswp.on('afterInit', updateCaption);
                    updateCaption();
                },
            });
        });

        lightbox.init();
    });
}
