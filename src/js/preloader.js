import { gsap } from 'gsap';

export function initPreloader() {
  const pre = document.getElementById('pg-preloader');
  const navLogo = document.querySelector('.nav-logo__img');
  const navMask = document.querySelector('.nav-logo__mask');

  // === NON-HOMEPAGE OR NO PRELOADER ===
  if (!pre) {
    // Ensure nav logo is visible and mask is gone
    if (navLogo && navMask) {
      gsap.set(navLogo, { opacity: 1 });
      gsap.set(navMask, { yPercent: -100 });
    }
    document.body.classList.remove('pg-content-hidden');
    return;
  }

  const logo = pre?.querySelector('.pg-preloader__logo');
  const mask = pre?.querySelector('.pg-preloader__mask');

  // === REFRESH / ALREADY SEEN ===
  if (sessionStorage.getItem('pgPreloaderSeen')) {
    pre.remove();

    if (navLogo && navMask) {
      gsap.set(navLogo, { opacity: 1 });
      gsap.set(navMask, { yPercent: -100 });
    }

    document.body.classList.remove('pg-content-hidden');
    gsap.to(['.nav__info-bar', 'main', 'footer'], {
      opacity: 1,
      duration: 0.3,
      ease: 'power1.out',
    });
    return;
  }
  sessionStorage.setItem('pgPreloaderSeen', '1');

  // === FIRST VISIT ===
  if (logo && mask && navLogo && navMask) {
    const tl = gsap.timeline({
      onComplete: () => pre.remove(),
    });

    // Hold + white logo fade in
    tl.to({}, { duration: 0.6 });
    tl.to(logo, { opacity: 1, duration: 0.9, ease: 'power2.out' });
    tl.to({}, { duration: 0.8 });

    // White logo masks out
    tl.to(mask, { yPercent: -100, duration: 0.55, ease: 'power4.in' });

    // Black nav logo reveal + background fade start together
    tl.set(navLogo, { opacity: 1 });
    tl.fromTo(navMask, { yPercent: 0 }, { yPercent: -100, duration: 0.55, ease: 'power4.in' });
    tl.to(pre, { opacity: 0, duration: 0.45, ease: 'power2.out' }, '<');

    // Fade in content after
    tl.to(['.nav__info-bar', 'main', 'footer'], {
      opacity: 1,
      duration: 0.8,
      ease: 'power2.out',
    });

    tl.add(() => document.body.classList.remove('pg-content-hidden'));
  }
}
