function simplePreloader() {
  const pre = document.getElementById('pg-preloader');
  if (!pre) return;

  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // If we've shown it in this tab/window already, skip immediately
  if (sessionStorage.getItem('pg_preloader_seen') === '1') {
    pre.remove();
    return;
  }

  document.documentElement.classList.add('pg-is-preloading');

  const finish = () => {
    // Mark as seen for this session (persists across refreshes in this tab)
    sessionStorage.setItem('pg_preloader_seen', '1');
    pre.remove();
    document.documentElement.classList.remove('pg-is-preloading');
  };

  // Respect reduced motion: no animation, but still mark as seen
  if (reduce) {
    finish();
    return;
  }

  const run = () => {
    gsap.to(pre, {
      autoAlpha: 0,
      duration: 0.6,
      delay: 0.1,
      onComplete: finish,
    });
  };

  if (document.readyState === 'complete') run();
  else window.addEventListener('load', run, { once: true });
}
