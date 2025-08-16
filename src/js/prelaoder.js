// src/js/preloader.js
import { gsap } from 'gsap';

// Helper: respect reduced motion
const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export function runPreloader() {
  if (!document.body.classList.contains('home')) return;

  const preloader = document.getElementById('pg-preloader');
  if (!preloader) return;

  // Once per session
  const KEY = 'pg_preload_played';
  const alreadyPlayed = sessionStorage.getItem(KEY) === '1';

  const navLogo = document.querySelector('.pg-nav-logo');
  const bigLogo =
    preloader.querySelector('.pg-preloader__logo svg') ||
    preloader.querySelector('.pg-preloader__logo');

  // If reduced motion or already played, skip animation but ensure correct end state
  if (prefersReduced || alreadyPlayed) {
    preloader.classList.add('is-done');
    if (navLogo) navLogo.classList.add('is-visible');
    return;
  }

  // Safety: hide scroll while animating
  const originalOverflow = document.documentElement.style.overflow;
  document.documentElement.style.overflow = 'hidden';

  // Build the timeline
  const tl = gsap.timeline({
    defaults: { ease: 'power2.out' },
    onComplete: () => {
      preloader.classList.add('is-done');
      document.documentElement.style.overflow = originalOverflow || '';
      sessionStorage.setItem(KEY, '1');
    },
  });

  // Start state
  gsap.set(bigLogo, { yPercent: 0, scale: 1, opacity: 1, transformOrigin: '50% 50%' });

  // Animation steps:
  // 1) Slight scale-up + glow of the big white logo
  tl.to(bigLogo, { duration: 0.6, scale: 1.06 }, 0);

  // 2) Mask/slide the white logo upward (simulate a “reveal out”)
  tl.to(bigLogo, { duration: 0.9, yPercent: -120, opacity: 0 }, 0.2);

  // 3) Fade out the black overlay to reveal the white page
  tl.to(preloader, { duration: 0.6, opacity: 0 }, '-=0.3');

  // 4) Pop in the small black nav logo
  if (navLogo) {
    tl.call(() => navLogo.classList.add('is-visible'), null, '-=0.15');
  }
}
