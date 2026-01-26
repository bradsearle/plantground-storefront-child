// /assets/js/product-accordion.js
import { gsap } from 'gsap';

export function initProductAccordion() {
  const accordionHeaders = document.querySelectorAll('.prod-accordion__header');

  accordionHeaders.forEach((header) => {
    header.addEventListener('click', (e) => {
      e.preventDefault();

      const item = header.parentElement;
      const content = item.querySelector('.prod-accordion__content');
      const inner = item.querySelector('.prod-accordion__inner');
      const isActive = item.classList.contains('is-active');

      if (!isActive) {
        item.classList.add('is-active');

        // Height animation
        gsap.to(content, {
          height: inner.scrollHeight,
          duration: 0.7,
          ease: 'power3.inOut',
          onComplete: () => {
            gsap.set(content, { height: 'auto' });
          },
        });

        // Fade animation for the text
        gsap.fromTo(
          inner,
          { opacity: 0, y: 10 },
          { opacity: 1, y: 0, duration: 0.8, delay: 0.2, ease: 'power2.out' }
        );
      } else {
        item.classList.remove('is-active');

        // Quick fade out
        gsap.to(inner, { opacity: 0, duration: 0.3, ease: 'power2.in' });

        // Slide up
        gsap.fromTo(
          content,
          { height: content.offsetHeight },
          {
            height: 0,
            duration: 0.5,
            ease: 'power3.inOut',
          }
        );
      }
    });
  });
}
