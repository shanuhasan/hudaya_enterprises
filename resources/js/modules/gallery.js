/**
 * Gallery lightbox — built on the native <dialog> element
 * (focus is kept inside the dialog and Esc closes it natively).
 *
 * Markup:
 *   <figure data-gallery-item data-caption="…" data-brief="…"><button>…<img></button></figure>
 *   <dialog data-lightbox> … [data-lightbox-stage] [data-lightbox-caption] … </dialog>
 *
 * - Only items currently visible (not hidden by the filter) are navigable.
 * - ← / → keys, on-screen arrows and swipe gestures move between images.
 * - If a photo has not been supplied yet, its placeholder description is shown.
 */
const SWIPE_THRESHOLD = 50;

export default function initGallery() {
  const dialog = document.querySelector('[data-lightbox]');
  const items = [...document.querySelectorAll('[data-gallery-item]')];
  if (!dialog || !items.length || typeof dialog.showModal !== 'function') return;

  const stage = dialog.querySelector('[data-lightbox-stage]');
  const caption = dialog.querySelector('[data-lightbox-caption]');
  const counter = dialog.querySelector('[data-lightbox-counter]');

  let list = [];
  let index = 0;
  let opener = null;

  const visibleItems = () => items.filter((item) => !item.hidden);

  function render() {
    const item = list[index];
    const img = item.querySelector('img');
    stage.replaceChildren();

    if (img) {
      const large = new Image();
      large.src = img.src; // full-size file, not the small srcset variant
      large.alt = img.alt;
      large.decoding = 'async';
      stage.append(large);
    } else {
      const ph = document.createElement('div');
      ph.className = 'lightbox__ph';
      ph.textContent = `Image to come · ${item.dataset.brief}`;
      stage.append(ph);
    }

    caption.textContent = item.dataset.caption;
    counter.textContent = `${index + 1} / ${list.length}`;
    preloadNeighbours();
  }

  // Warm the cache for the previous/next image so arrows feel instant.
  function preloadNeighbours() {
    [-1, 1].forEach((delta) => {
      const next = list[(index + delta + list.length) % list.length];
      const src = next?.querySelector('img')?.src;
      if (src) new Image().src = src;
    });
  }

  function open(item) {
    list = visibleItems();
    index = Math.max(0, list.indexOf(item));
    opener = item.querySelector('button');
    render();
    dialog.showModal();
    document.documentElement.classList.add('is-locked');
  }

  const step = (delta) => {
    index = (index + delta + list.length) % list.length;
    render();
  };

  items.forEach((item) => item.querySelector('button')?.addEventListener('click', () => open(item)));

  dialog.querySelector('[data-lightbox-close]').addEventListener('click', () => dialog.close());
  dialog.querySelector('[data-lightbox-prev]').addEventListener('click', () => step(-1));
  dialog.querySelector('[data-lightbox-next]').addEventListener('click', () => step(1));

  dialog.addEventListener('keydown', (event) => {
    if (event.key === 'ArrowLeft') step(-1);
    if (event.key === 'ArrowRight') step(1);
  });

  // Click on the empty stage area closes the viewer.
  stage.addEventListener('click', (event) => {
    if (event.target === stage) dialog.close();
  });

  dialog.addEventListener('close', () => {
    document.documentElement.classList.remove('is-locked');
    stage.replaceChildren();
    opener?.focus();
  });

  // Swipe (touch / pen only; mostly-horizontal gestures)
  let startX = null;
  let startY = null;
  stage.addEventListener('pointerdown', (event) => {
    if (event.pointerType === 'mouse') return;
    startX = event.clientX;
    startY = event.clientY;
  }, { passive: true });
  stage.addEventListener('pointerup', (event) => {
    if (startX === null) return;
    const dx = event.clientX - startX;
    const dy = Math.abs(event.clientY - startY);
    if (Math.abs(dx) > SWIPE_THRESHOLD && Math.abs(dx) > dy) step(dx < 0 ? 1 : -1);
    startX = null;
  }, { passive: true });
  stage.style.touchAction = 'pan-y';
}
