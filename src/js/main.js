import '../sass/main.scss';
import { gsap } from 'gsap';
import { initFilters } from './filters.js';
import { initPreloader } from './preloader.js';
import { removeStickyBar } from './sticky-bar.js';
import infoBar from './info-bar.js';
import { initNavScroll } from './nav.js';
import { initCustomSelect } from './custom-select.js';
import { initCartCount } from './cart-count.js';
import { initCustomProductGallery } from './custom-gallery.js';

// ... all your other imports ...
import { initFullscreenMenu } from './fullscreen-menu.js';

// Run on DOM ready (if not already handled)
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initCustomProductGallery);
} else {
  initCustomProductGallery();
}

// JUST ADD THIS ONE LINE AT THE VERY BOTTOM OF IMPORTS
// import './hero-parallax.js';

// Run features (Keep exactly as you have it)
initFilters();
removeStickyBar();
initPreloader();
infoBar();
initNavScroll();
initCustomSelect();
fullscreenMenu();
initCartCount();
