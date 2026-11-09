/**
 * Navigation drawer — accessible dialog.
 * - Open: [data-menu-open]. Close: [data-menu-close] (button or scrim), Esc, link click,
 *   swipe right on touch, or resizing to desktop.
 * - Traps focus while open and returns it to the opener on close.
 * - Locks page scroll while open (with scrollbar compensation, so the page doesn't jump).
 * Markup: <div id="mobile-menu" data-menu hidden role="dialog" aria-modal="true">
 *           <div data-menu-close></div> <div data-menu-panel>…</div>
 *         </div>
 */
const DESKTOP_QUERY = '(min-width: 80rem)';
const FOCUSABLE = 'a[href], button:not([disabled]), input, select, textarea, [tabindex]:not([tabindex="-1"])';
const CLOSE_MS = 420;       // matches --dur-drawer + a small buffer
const SWIPE_CLOSE_PX = 60;

export function initMobileMenu() {
  const menu = document.querySelector('[data-menu]');
  const panel = menu?.querySelector('[data-menu-panel]');
  const openers = document.querySelectorAll('[data-menu-open]');
  if (!menu || !panel || !openers.length) return;

  const root = document.documentElement;
  let lastFocused = null;
  let hideTimer = null;

  const setExpanded = (value) =>
    openers.forEach((btn) => btn.setAttribute('aria-expanded', String(value)));

  function lockScroll(lock) {
    if (lock) {
      const scrollbar = window.innerWidth - root.clientWidth;
      root.style.setProperty('--scrollbar-comp', `${scrollbar}px`);
    }
    root.classList.toggle('is-locked', lock);
  }

  function open() {
    clearTimeout(hideTimer);
    lastFocused = document.activeElement;
    menu.hidden = false;
    lockScroll(true);
    setExpanded(true);
    // Next frame so the transition runs after display changes.
    requestAnimationFrame(() => {
      requestAnimationFrame(() => menu.classList.add('is-open'));
      panel.querySelector(FOCUSABLE)?.focus({ preventScroll: true });
    });
    document.addEventListener('keydown', onKeydown);
  }

  function close({ restoreFocus = true } = {}) {
    if (menu.hidden || !menu.classList.contains('is-open')) return;
    menu.classList.remove('is-open');
    lockScroll(false);
    setExpanded(false);
    document.removeEventListener('keydown', onKeydown);

    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    hideTimer = setTimeout(() => { menu.hidden = true; }, reduceMotion ? 0 : CLOSE_MS);

    if (restoreFocus && lastFocused instanceof HTMLElement) lastFocused.focus({ preventScroll: true });
  }

  function onKeydown(event) {
    if (event.key === 'Escape') {
      event.preventDefault();
      close();
      return;
    }
    if (event.key !== 'Tab') return;

    const items = [...panel.querySelectorAll(FOCUSABLE)].filter((el) => el.offsetParent !== null);
    if (!items.length) return;
    const first = items[0];
    const last = items[items.length - 1];

    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  }

  openers.forEach((btn) => btn.addEventListener('click', open));
  menu.querySelectorAll('[data-menu-close]').forEach((el) => el.addEventListener('click', () => close()));

  // Following a link (incl. same-page anchors) closes the drawer.
  panel.querySelectorAll('a[href]').forEach((link) =>
    link.addEventListener('click', () => close({ restoreFocus: false })),
  );

  // Swipe right to close (touch / pen only).
  let startX = null;
  let startY = null;
  panel.addEventListener('pointerdown', (event) => {
    if (event.pointerType === 'mouse') return;
    startX = event.clientX;
    startY = event.clientY;
  }, { passive: true });
  panel.addEventListener('pointerup', (event) => {
    if (startX === null) return;
    const dx = event.clientX - startX;
    const dy = Math.abs(event.clientY - startY);
    if (dx > SWIPE_CLOSE_PX && dx > dy) close();
    startX = null;
  }, { passive: true });

  window.matchMedia(DESKTOP_QUERY).addEventListener('change', (event) => {
    if (event.matches) close({ restoreFocus: false });
  });
}
