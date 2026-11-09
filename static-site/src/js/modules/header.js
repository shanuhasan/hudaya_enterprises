/**
 * Scroll state — one passive, rAF-throttled scroll listener for the whole site.
 * - .is-scrolled on [data-site-header]  → solid navbar, announcement bar tucks away
 * - html.has-scrolled                   → floating WhatsApp button appears
 * - html.has-scrolled-far               → back-to-top button appears (after ~one screen)
 * Markup: <div data-site-header>  (not [data-header] — that attribute is on <body>)
 */
const SOLID_AFTER = 10;        // px before the navbar turns solid
const WHATSAPP_AFTER = 240;    // px before the WhatsApp button shows
const TOP_AFTER_RATIO = 0.9;   // × viewport height before back-to-top shows

export function initHeader() {
  const header = document.querySelector('[data-site-header]');
  const root = document.documentElement;
  let ticking = false;

  const update = () => {
    const y = window.scrollY;
    header?.classList.toggle('is-scrolled', y > SOLID_AFTER);
    root.classList.toggle('has-scrolled', y > WHATSAPP_AFTER);
    root.classList.toggle('has-scrolled-far', y > window.innerHeight * TOP_AFTER_RATIO);
    ticking = false;
  };

  window.addEventListener(
    'scroll',
    () => {
      if (!ticking) {
        window.requestAnimationFrame(update);
        ticking = true;
      }
    },
    { passive: true },
  );

  // Covers reloads / anchor links that open the page already scrolled.
  window.addEventListener('load', update);
  // First read on the next frame, after icons have rendered (avoids a forced layout).
  window.requestAnimationFrame(update);
}
