// /assets/js/main.js

import '../sass/main.scss';

// 1. Side-effect import (This runs the menu code immediately)
import './fullscreen-menu.js';

// 2. Named imports for your other features
import { gsap } from 'gsap';
import { initFilters } from './filters.js';
import { initPreloader } from './preloader.js';
import { removeStickyBar } from './sticky-bar.js';
import infoBar from './info-bar.js';
import { initNavScroll } from './nav.js';
import { initCustomSelect } from './custom-select.js';
import { initCartCount } from './cart-count.js';
import { initProductGalleryModal } from './product-gallery-modal.js';

// 3. Initialize everything
function initApp() {
  initFilters();
  removeStickyBar();
  initPreloader();
  infoBar();
  initNavScroll();
  initCustomSelect();
  initCartCount();

  // This starts the modal logic we built
  initProductGalleryModal();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initApp);
} else {
  initApp();
}

document.body.classList.remove('woocommerce-no-js');
document.body.classList.add('woocommerce-js');
