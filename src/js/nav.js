// nav.js
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export function initNavScroll() {
  gsap.registerPlugin(ScrollTrigger);

  const nav = document.querySelector('nav');
  if (!nav) return;

  let scrollTween = gsap
    .from(nav, {
      yPercent: -100,
      paused: true,
      duration: 0.2,
    })
    .progress(1);

  ScrollTrigger.create({
    start: 'top top',
    end: 'max',
    onUpdate: (self) => {
      self.direction === -1 ? scrollTween.play() : scrollTween.reverse();
    },
  });
}
