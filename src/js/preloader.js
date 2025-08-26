import { gsap } from 'gsap';

export function initPreloader() {
  if (!document.body.classList.contains('home')) return;

  const pre = document.getElementById('pg-preloader');
  if (!pre) return;

  if (sessionStorage.getItem('pgPreloaderSeen')) {
    pre.remove();
    return;
  }
  sessionStorage.setItem('pgPreloaderSeen', '1');

  const logo = pre.querySelector('.pg-preloader__logo');
  const mask = pre.querySelector('.pg-preloader__mask');

  const tl = gsap.timeline({
    onComplete: () => pre.remove(),
  });

  // 1. Hold black for a moment
  tl.to({}, { duration: 0.6 });

  // 2. Logo fades + scales in (clear and noticeable)
  tl.to(logo, { opacity: 1, scale: 1, duration: 0.9, ease: 'power3.out' });

  // 3. Hold logo visible
  tl.to({}, { duration: 0.8 });

  // 4. Mask wipes UP to cover logo with stronger ease
  tl.to(mask, { yPercent: -100, duration: 0.5, ease: 'expo.in' });

  // 5. Fade out background after mask completes
  tl.to(pre, { opacity: 0, duration: 0.7, ease: 'power2.out' });
}
