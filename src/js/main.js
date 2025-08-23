// main.js
import '../sass/main.scss';
import { gsap } from 'gsap';
import { initFilters } from './filters.js';
import { initPreloader } from './preloader.js';
import { removeStickyBar } from './sticky-bar.js';
import infoBar from './info-bar.js';
import { initNavScroll } from './nav.js';

// Run features
initFilters();
removeStickyBar();
initPreloader();
infoBar();
initNavScroll();
