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
        isLargeDesktop: '(min-width: 1401px)',
        isMediumDesktop: '(min-width: 768px) and (max-width: 1400px)',
        isMobile: '(max-width: 767px)',
      },
      (context) => {
        let { isLargeDesktop, isMediumDesktop } = context.conditions;
        const isDesktop = isLargeDesktop || isMediumDesktop;

        const targetImg = isDesktop
          ? hero.querySelector('.desktop-img')
          : hero.querySelector('.mobile-img');

        if (!targetImg) return;

        // Clear any existing ScrollTrigger to avoid duplicates
        ScrollTrigger.getAll().forEach((st) => st.kill());

        gsap.set(targetImg, {
          scale: 1.15,
          top: '50%',
          left: '50%',
          xPercent: -50,
          yPercent: -50,
        });

        gsap.to(targetImg, {
          yPercent: -60,
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

  window.addEventListener('load', init);
})();
