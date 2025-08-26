import { gsap } from 'gsap';

export function initPreloader() {
  if (!document.body.classList.contains('home')) return;

  const pre = document.getElementById('pg-preloader');
  const logo = pre?.querySelector('.pg-preloader__logo');
  const mask = pre?.querySelector('.pg-preloader__mask');

  const navLogo = document.querySelector('.nav-logo__img');
  const navMask = document.querySelector('.nav-logo__mask');

  function runNavLogoReveal() {
    if (navLogo && navMask) {
      const tl = gsap.timeline({
        onComplete: () => document.body.classList.remove('pg-content-hidden'),
      });

      // reveal black nav logo
      tl.set(navLogo, { opacity: 1 });
      tl.fromTo(
        navMask,
        { yPercent: 0 }, // fully covering
        { yPercent: -100, duration: 0.6, ease: 'expo.inOut' } // slide up to reveal
      );

      // fade in info bar + rest of page
      tl.to(['.nav__info-bar', 'main', 'footer'], {
        opacity: 1,
        duration: 0.8,
        ease: 'power2.out',
      });
    } else {
      document.body.classList.remove('pg-content-hidden');
    }
  }

  // === REFRESH / PRELOADER ALREADY SEEN ===
  if (sessionStorage.getItem('pgPreloaderSeen')) {
    pre?.remove();

    if (navLogo && navMask) {
      gsap.set(navLogo, { opacity: 1 }); // make sure logo is visible
      gsap.set(navMask, { yPercent: -100 }); // mask out of the way
    }

    document.body.classList.remove('pg-content-hidden');

    gsap.to(['.nav__info-bar', 'main', 'footer'], {
      opacity: 1,
      duration: 0.3,
    });
    return;
  }
  sessionStorage.setItem('pgPreloaderSeen', '1');

  // === FIRST VISIT: PRELOADER TIMELINE ===
  if (pre && logo && mask) {
    const tl = gsap.timeline({
      onComplete: () => pre.remove(),
    });

    tl.to({}, { duration: 0.6 }); // short hold
    tl.to(logo, { opacity: 1, duration: 0.9, ease: 'power3.out' }); // white logo fade in
    tl.to({}, { duration: 0.8 }); // hold white logo
    tl.to(mask, {
      yPercent: -100,
      duration: 0.5,
      ease: 'expo.in',
      onComplete: runNavLogoReveal, // trigger black logo reveal as white logo finishes
    });
    tl.to(pre, { opacity: 0, duration: 0.7, ease: 'power2.out' }); // fade out black bg
  }
}
