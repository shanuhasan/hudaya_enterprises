/**
 * Scroll reveal — subtle one-time fade-in as elements enter the viewport.
 * - <div data-reveal>                        single element
 * - <ul data-reveal-group><li data-reveal>   children stagger (60ms, capped at 5 steps
 *                                            so long lists never feel slow)
 * Content is fully visible without JS and under prefers-reduced-motion.
 * Uses one IntersectionObserver (no scroll listeners).
 */
const MAX_STAGGER_STEPS = 5;

export function initReveal() {
  const items = document.querySelectorAll('[data-reveal]');
  if (!items.length) return;

  document.querySelectorAll('[data-reveal-group]').forEach((group) => {
    group.querySelectorAll('[data-reveal]').forEach((el, i) => {
      el.style.setProperty('--reveal-i', String(i % (MAX_STAGGER_STEPS + 1)));
    });
  });

  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduceMotion || !('IntersectionObserver' in window)) {
    items.forEach((el) => el.classList.add('is-visible'));
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    },
    { rootMargin: '0px 0px -8% 0px', threshold: 0.08 },
  );

  items.forEach((el) => observer.observe(el));
}
