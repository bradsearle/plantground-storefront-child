// nav.js
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

let navScrollTrigger = null;

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
  navScrollTrigger = ScrollTrigger.create({
    start: 'top top',
    end: 'max',
    onUpdate: (self) => {
      self.direction === -1 ? scrollTween.play() : scrollTween.reverse();
    },
  });

  return navScrollTrigger;
}

export function getNavScrollTrigger() {
  return navScrollTrigger;
}
