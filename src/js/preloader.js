import { gsap } from 'gsap';

export function initPreloader() {
  const pre = document.getElementById('pg-preloader');
  const navLogo = document.querySelector('.nav-logo__img');
  const navMask = document.querySelector('.nav-logo__mask');

  // === NON-HOMEPAGE CASE ===
  if (!pre) {
    if (navLogo && navMask) {
      gsap.set(navLogo, { opacity: 1 });
      gsap.set(navMask, { yPercent: -100 });
    }
    document.body.classList.remove('pg-content-hidden');
    return;
  }

  const logo = pre?.querySelector('.pg-preloader__logo');
  const mask = pre?.querySelector('.pg-preloader__mask');

  // === REFRESH CASE ===
  if (sessionStorage.getItem('pgPreloaderSeen')) {
    pre.remove();

    if (navLogo && navMask) {
      gsap.set(navLogo, { opacity: 1 });
      gsap.set(navMask, { yPercent: -100 });
    }

    document.body.classList.remove('pg-content-hidden');
    gsap.to(['.nav__info-bar', 'main', 'footer'], {
      opacity: 1,
      duration: 0.4,
      ease: 'power1.out',
      stagger: 0.15,
    });
    return;
  }
  sessionStorage.setItem('pgPreloaderSeen', '1');

  // === FIRST VISIT ===
  if (logo && mask && navLogo && navMask) {
    const tl = gsap.timeline({
      onComplete: () => pre.remove(),
    });

    // subtle hold before fade-in
    tl.to({}, { duration: 0.4 });

    // smooth fade-in of white logo
    tl.to(logo, { opacity: 1, duration: 1.3, ease: 'circ.out' });

    // immediately mask white logo away (fast + snappy)
    tl.to(mask, { yPercent: -100, duration: 0.45, ease: 'power3.inOut' });

    // black nav logo reveal + background fade start TOGETHER
    tl.set(navLogo, { opacity: 1 });
    tl.fromTo(
      navMask,
      { yPercent: 0 },
      { yPercent: -100, duration: 0.45, ease: 'power3.inOut' },
      '<' // start at same time as background fade
    );
    tl.to(
      pre,
      { opacity: 0, duration: 0.5, ease: 'power2.out' },
      '<' // synced with nav logo reveal
    );

    // staggered fade-in of page content
    tl.to(['.nav__info-bar', 'main', 'footer'], {
      opacity: 1,
      duration: 0.8,
      ease: 'power2.out',
      stagger: 0.15,
    });

    tl.add(() => document.body.classList.remove('pg-content-hidden'));
  }
}
