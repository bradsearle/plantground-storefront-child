// /assets/js/main.js

import '../sass/main.scss';
import './fullscreen-menu.js'; // ← no destructuring, no function call
import { gsap } from 'gsap';
import { initFilters } from './filters.js';
import { initPreloader } from './preloader.js';
import { removeStickyBar } from './sticky-bar.js';
import infoBar from './info-bar.js';
import { initNavScroll } from './nav.js';
import { initCustomSelect } from './custom-select.js';
import { initCartCount } from './cart-count.js';

// ✅ Only ONE import for the gallery modal
import { initProductGalleryModal } from './product-gallery-modal.js';

// ... other imports ...
import { initFullscreenMenu } from './fullscreen-menu.js';

// Run DOM-dependent features
function initApp() {
  initFilters();
  removeStickyBar();
  initPreloader();
  infoBar();
  initNavScroll();
  initCustomSelect();
  initCartCount();

  initProductGalleryModal(); // ← runs on all pages, but safely exits if no gallery
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initApp);
} else {
  initApp();
}
