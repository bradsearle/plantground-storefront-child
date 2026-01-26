// /assets/js/product-accordion.js
import { gsap } from 'gsap';

export function initProductAccordion() {
  const accordionHeaders = document.querySelectorAll('.prod-accordion__header');

  accordionHeaders.forEach((header) => {
    let isAnimating = false; // Prevent double-click glitches

    header.addEventListener('click', (e) => {
      e.preventDefault();
      if (isAnimating) return;

      const item = header.parentElement;
      const content = item.querySelector('.prod-accordion__content');
      const inner = item.querySelector('.prod-accordion__inner');
      const isActive = item.classList.contains('is-active');

      isAnimating = true;

      if (!isActive) {
        // OPENING
        item.classList.add('is-active');

        // 1. Height Animation
        gsap.to(content, {
          height: inner.scrollHeight,
          duration: 0.7,
          ease: 'power3.inOut',
          onComplete: () => {
            gsap.set(content, { height: 'auto' });
            isAnimating = false;
          },
        });

        // 2. Premium Fade/Slide Animation
        gsap.fromTo(
          inner,
          { opacity: 0, y: 15 },
          {
            opacity: 1,
            y: 0,
            duration: 0.8,
            delay: 0.1,
            ease: 'power2.out',
          }
        );
      } else {
        // CLOSING
        item.classList.remove('is-active');

        // 1. Fast Fade Out
        gsap.to(inner, {
          opacity: 0,
          y: 10,
          duration: 0.3,
          ease: 'power2.in',
        });

        // 2. Slide Up
        gsap.fromTo(
          content,
          { height: content.offsetHeight },
          {
            height: 0,
            duration: 0.6,
            ease: 'power3.inOut',
            onComplete: () => {
              isAnimating = false;
            },
          }
        );
      }
    });
  });
}
