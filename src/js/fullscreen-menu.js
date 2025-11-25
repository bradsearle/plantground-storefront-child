// fullscreen-menu.js
import { gsap } from 'gsap';
import { getNavScrollTrigger } from './nav.js';

export function initFullscreenMenu() {
  const menuToggle = document.getElementById('menuToggle');
  const fullscreenMenu = document.getElementById('fullscreenMenu');
  const menuItems = document.querySelectorAll('.fullscreen-menu__item');
  const nav = document.querySelector('nav');

  if (!menuToggle || !fullscreenMenu) {
    console.warn('Menu elements not found');
    return;
  }

  let isMenuOpen = false;

  // Function to disable nav scroll behavior when menu is open
  function disableNavScroll() {
    const scrollTrigger = getNavScrollTrigger();
    if (scrollTrigger) {
      scrollTrigger.disable();
    }
    if (nav) {
      gsap.to(nav, { yPercent: 0, duration: 0.3 });
    }
  }

  // Function to enable nav scroll behavior when menu is closed
  function enableNavScroll() {
    const scrollTrigger = getNavScrollTrigger();
    if (scrollTrigger) {
      scrollTrigger.enable();
    }
  }

  // Open menu
  function openMenu() {
    isMenuOpen = true;
    fullscreenMenu.classList.add('active');
    menuToggle.classList.add('menu-open');
    document.body.classList.add('fullscreen-menu-open'); // Hide nav elements
    document.documentElement.classList.add('fullscreen-menu-open'); // Hide scrollbar on html
    document.body.style.overflow = 'hidden';
    menuToggle.textContent = 'CLOSE';
    disableNavScroll();

    // Instant appearance - no animation
    gsap.set(fullscreenMenu, { opacity: 1 });
    gsap.set(menuItems, { opacity: 1, y: 0 });
  }

  // Close menu - instant close
  function closeMenu() {
    isMenuOpen = false;
    menuToggle.classList.remove('menu-open');
    document.body.classList.remove('fullscreen-menu-open'); // Show nav elements again
    document.documentElement.classList.remove('fullscreen-menu-open'); // Restore scrollbar on html
    menuToggle.textContent = 'MENU';

    // Instant close - no animation
    fullscreenMenu.classList.remove('active');
    document.body.style.overflow = '';
    enableNavScroll();

    // Reset menu items instantly
    gsap.set(menuItems, { opacity: 0, y: 20 });
    gsap.set(fullscreenMenu, { opacity: 0 });
  }

  // Toggle menu
  function toggleMenu() {
    if (isMenuOpen) {
      closeMenu();
    } else {
      openMenu();
    }
  }

  // Click handler for menu toggle button
  menuToggle.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    toggleMenu();
  });

  // Close menu when clicking on a link
  const menuLinks = document.querySelectorAll('.fullscreen-menu__link');
  menuLinks.forEach((link) => {
    link.addEventListener('click', () => {
      if (isMenuOpen) {
        closeMenu();
      }
    });
  });

  // Close menu on ESC key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isMenuOpen) {
      closeMenu();
    }
  });
}
