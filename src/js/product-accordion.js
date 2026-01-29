import { gsap } from 'gsap';

export function initProductAccordion() {
  const accordionHeaders = document.querySelectorAll('.prod-accordion__header');

  accordionHeaders.forEach((header) => {
    let isAnimating = false;

    const item = header.parentElement;
    const content = item.querySelector('.prod-accordion__content');
    const inner = item.querySelector('.prod-accordion__inner');
    const backToTop = inner.querySelector('.accordion-back-to-top');

    // --- 1. The Toggle Logic ---
    header.addEventListener('click', (e) => {
      e.preventDefault();
      if (isAnimating) return;

      const isActive = item.classList.contains('is-active');
      isAnimating = true;

      if (!isActive) {
        // OPENING
        item.classList.add('is-active');

        gsap.to(content, {
          height: inner.scrollHeight,
          duration: 0.7,
          ease: 'power3.inOut',
          onComplete: () => {
            gsap.set(content, { height: 'auto' });
            isAnimating = false;
          },
        });

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

        gsap.to(inner, {
          opacity: 0,
          y: 10,
          duration: 0.3,
          ease: 'power2.in',
        });

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

    // --- 2. The Back to Top Logic ---
    if (backToTop) {
      backToTop.addEventListener('click', (e) => {
        e.preventDefault();

        // Calculate position: header top - offset for sticky nav (e.g. 80px)
        const headerOffset = 80;
        const elementPosition = header.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth',
        });
      });
    }
  });
}
