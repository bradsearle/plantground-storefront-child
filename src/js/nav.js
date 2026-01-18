import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export function initNavScroll() {
  gsap.registerPlugin(ScrollTrigger);

  const nav = document.querySelector('.nav__flex');
  if (!nav) return;

  gsap.set(nav, { position: 'fixed', top: '40px', y: 0 });

  ScrollTrigger.create({
    start: 0,
    end: 'max',
    onUpdate: (self) => {
      const scrollPos = self.scroll();
      const isScrollingDown = self.direction === 1;

      // ZONE A: THE NATURAL TOP (0px - 40px)
      if (scrollPos <= 40) {
        gsap.to(nav, {
          y: -scrollPos,
          duration: 0.1, // Slight soften so it doesn't "vibrate" at the top
          ease: 'none',
          overwrite: true,
        });
      }

      // ZONE B: THE DEEP PAGE (Past 40px)
      else {
        if (isScrollingDown) {
          // HIDE: Keep this fast (0.2s) so it gets out of the way of content
          gsap.to(nav, {
            y: -150,
            duration: 0.2,
            ease: 'power1.in',
            overwrite: 'auto',
          });
        } else {
          // REVEAL: Slower and smoother
          // Increased duration to 0.7s and used power3.out for a "glide" feel
          gsap.to(nav, {
            y: -40,
            duration: 0.7,
            ease: 'power3.out',
            overwrite: 'auto',
          });
        }
      }
    },
  });
}
