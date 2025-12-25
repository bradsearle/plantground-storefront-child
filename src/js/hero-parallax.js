import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

(function () {
  gsap.registerPlugin(ScrollTrigger);

  const init = () => {
    const hero = document.querySelector('.hero-banner');
    if (!hero) return;

    let mm = gsap.matchMedia();

    mm.add(
      {
        isDesktop: '(min-width: 768px)',
        isMobile: '(max-width: 767px)',
      },
      (context) => {
        const { isDesktop } = context.conditions;

        // Select the correct image based on device
        const targetImg = isDesktop
          ? hero.querySelector('.desktop-img')
          : hero.querySelector('.mobile-img');

        if (!targetImg) return;

        /**
         * FIX 1: REMOVED ScrollTrigger.getAll().forEach(...)
         * gsap.matchMedia automatically handles cleaning up triggers
         * created inside this function when the screen resizes.
         * Deleting that line restores your Nav scroll functionality.
         */

        // 1. Initial Set: Pin to bottom, handle horizontal centering
        gsap.set(targetImg, {
          scale: 1.05,
          bottom: 0,
          top: 'auto',
          xPercent: -50,
          yPercent: 0,
          transformOrigin: 'bottom center',
        });

        // 2. The Animation
        gsap.to(targetImg, {
          yPercent: 5, // Toned down from 10 to keep the "smaller" look stable
          ease: 'none',
          scrollTrigger: {
            trigger: hero,
            start: 'top top',
            end: 'bottom top',
            scrub: isDesktop ? 1.5 : 0.5,
            invalidateOnRefresh: true,
          },
        });
      }
    );
  };

  // Using 'DOMContentLoaded' is often safer, but 'load' works
  // if you need to wait for heavy images to finish loading.
  window.addEventListener('load', init);
})();
