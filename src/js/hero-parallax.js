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
        let { isDesktop } = context.conditions;

        const targetImg = isDesktop
          ? hero.querySelector('.desktop-img')
          : hero.querySelector('.mobile-img');

        if (targetImg) {
          // Sync heights: Desktop 140% | Mobile 170%
          const imgHeight = isDesktop ? '140%' : '170%';

          gsap.set(targetImg, {
            top: '50%',
            left: '50%',
            xPercent: -50,
            yPercent: -50,
            height: imgHeight,
            width: '100%',
            scale: 1,
            objectFit: 'cover',
          });

          // Animation:
          // Desktop: -50 to -65 (15% move - feels strong on a short banner)
          // Mobile: -50 to -70 (20% move)
          const targetY = isDesktop ? -65 : -70;

          gsap.to(targetImg, {
            yPercent: targetY,
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
      }
    );
  };

  window.addEventListener('load', init);
})();
