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

        // Target only the active image
        const targetImg = isDesktop
          ? hero.querySelector('.desktop-img')
          : hero.querySelector('.mobile-img');

        if (targetImg) {
          // We set the initial scale here.
          // 0.8 = Shrunk/Zoomed Out
          // 1.0 = Original
          gsap.set(targetImg, {
            height: '110%',
            scale: 1,
            transformOrigin: 'center center',
            objectFit: 'cover',
          });

          gsap.to(targetImg, {
            yPercent: -10,
            ease: 'none',
            scrollTrigger: {
              trigger: hero,
              start: 'top top',
              end: 'bottom top',
              scrub: true,
              invalidateOnRefresh: true,
            },
          });
        }
      }
    );
  };

  // Wait for full load to protect your preloader sequence
  window.addEventListener('load', init);
})();
