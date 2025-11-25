import '../sass/main.scss';
import { gsap } from 'gsap';
import { initFilters } from './filters.js';
import { initPreloader } from './preloader.js';
import { removeStickyBar } from './sticky-bar.js';
import infoBar from './info-bar.js';
import { initNavScroll } from './nav.js';
import { initCustomSelect } from './custom-select.js';
import { initFullscreenMenu } from './fullscreen-menu.js';

// Run features
initFilters();
removeStickyBar();
initPreloader();
infoBar();
initNavScroll();
initCustomSelect();
initFullscreenMenu();
