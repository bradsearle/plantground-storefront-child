// preloader.js
import { gsap } from 'gsap';

export function initPreloader() {
  // Only run on homepage
  if (!document.body.classList.contains('home')) return;

  // Only run once per session
  if (sessionStorage.getItem('pgPreloaderSeen')) return;

  const pre = document.getElementById('pg-preloader');
  if (!pre) return;

  const logo = pre.querySelector('.pg-preloader__logo');
  if (!logo) return;

  // Mark preloader as seen for this session
  sessionStorage.setItem('pgPreloaderSeen', '1');

  const tl = gsap.timeline({ defaults: { ease: 'power2.out' } });

  // 1) Fade in logo
  tl.to(logo, { opacity: 1, duration: 0.6 });

  // 2) Hold black screen for at least ~2 seconds total
  tl.to({}, { duration: 1.4 });

  // 3) Mask-up effect: slide logo upward behind black mask
  tl.to(logo, { y: -200, duration: 0.55, ease: 'power4.in' });

  // 4) Fade out overlay
  tl.to(pre, { opacity: 0, duration: 0.5, ease: 'power2.out' });

  // 5) Remove from DOM so it doesn't block clicks
  tl.add(() => pre.remove());
}
