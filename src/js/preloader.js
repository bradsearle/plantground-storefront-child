import { gsap } from 'gsap';

export function initPreloader() {
  const pre = document.getElementById('pg-preloader');
  const navLogo = document.querySelector('.nav-logo__img');

  // === NON-HOMEPAGE CASE ===
  if (!pre) {
    if (navLogo) gsap.set(navLogo, { y: 0, opacity: 1 });
    document.body.classList.remove('pg-content-hidden');
    return;
  }

  const logo = pre?.querySelector('.pg-preloader__logo');

  // === REFRESH CASE ===
  if (sessionStorage.getItem('pgPreloaderSeen')) {
    pre.remove();
    if (navLogo) gsap.set(navLogo, { y: 0, opacity: 1 });
    document.body.classList.remove('pg-content-hidden');
    gsap.to(['main', 'footer'], {
      opacity: 1,
      duration: 0.4,
      ease: 'power1.out',
      stagger: 0.15,
    });
    return;
  }
  sessionStorage.setItem('pgPreloaderSeen', '1');

  // === FIRST VISIT ===
  if (logo && navLogo) {
    const tl = gsap.timeline({
      onComplete: () => pre.remove(),
    });

    // 1) Fade in white FFF logo
    tl.to(logo, { opacity: 1, duration: 1.0, ease: 'circ.out' });

    // 2) Slide FFF logo up and out
    tl.to(logo, { y: -120, duration: 0.6, ease: 'power3.in' });

    // 3) Black 000 logo slides up from below
    tl.set(navLogo, { opacity: 1, y: 200 });
    tl.to(navLogo, { y: 0, duration: 0.8, ease: 'power4.out' });

    // 4) Fade out green background AFTER black logo finishes
    tl.to(pre, { opacity: 0, duration: 0.6, ease: 'power2.out' });

    // 5) Fade in content
    tl.to(['main', 'footer'], {
      opacity: 1,
      duration: 0.8,
      ease: 'power2.out',
      stagger: 0.15,
    });

    tl.add(() => document.body.classList.remove('pg-content-hidden'));
  }
}
