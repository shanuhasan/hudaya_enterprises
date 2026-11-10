/**
 * Back to top — smooth scroll (instant under reduced motion), then moves keyboard
 * focus to the logo so keyboard and screen-reader users continue from the top.
 * Markup: <button data-back-to-top>
 */
export function initBackToTop() {
  const button = document.querySelector('[data-back-to-top]');
  if (!button) return;

  button.addEventListener('click', () => {
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
    document.querySelector('.site-header .logo')?.focus({ preventScroll: true });
  });
}
