// /assets/js/main.js

import '../sass/main.scss';
import './fullscreen-menu.js';
import { gsap } from 'gsap';
import { initFilters } from './filters.js';
import { initPreloader } from './preloader.js';
import { removeStickyBar } from './sticky-bar.js';
import infoBar from './info-bar.js';
import { initNavScroll } from './nav.js';
import { initCustomSelect } from './custom-select.js';
import { initCartCount } from './cart-count.js';
import { initProductGalleryModal } from './product-gallery-modal.js';

function initMobileCartStick() {
  const cartForm = document.querySelector('form.cart');
  const gallery = document.querySelector('.plantground-product-gallery');

  if (window.innerWidth <= 1024 && cartForm && gallery) {
    const handleScroll = () => {
      const galleryBottom = gallery.getBoundingClientRect().bottom;
      const viewportHeight = window.innerHeight;

      // Logic: If the gallery bottom is still below the screen's bottom, stay fixed.
      // The moment the gallery bottom rises ABOVE the screen's bottom, release.
      if (galleryBottom > viewportHeight) {
        cartForm.classList.add('is-fixed-mobile');
      } else {
        cartForm.classList.remove('is-fixed-mobile');
      }
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll(); // Run once on load
  }
}

function initApp() {
  initFilters();
  removeStickyBar();
  initPreloader();
  infoBar();
  initNavScroll();
  initCustomSelect();
  initCartCount();
  initProductGalleryModal();
  initMobileCartStick();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initApp);
} else {
  initApp();
}

document.body.classList.remove('woocommerce-no-js');
document.body.classList.add('woocommerce-js');
