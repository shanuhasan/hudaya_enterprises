/**
 * Hudaya Enterprises — JS entry.
 * Global modules run on every page; each one exits early if its markup is absent.
 * Page-specific modules (gallery, filters, forms) are lazy-loaded so pages only
 * download what they use. This file moves unchanged to Laravel resources/js/.
 */
import { initIcons } from './modules/icons.js';
import { initHeader } from './modules/header.js';
import { initMobileMenu } from './modules/mobile-menu.js';
import { initReveal } from './modules/reveal.js';
import { initBackToTop } from './modules/back-to-top.js';

// [selector, () => import(module)] — added as pages are built.
const lazyModules = [
  ['[data-collection-filter]', () => import('./modules/collection-filter.js')],
  ['[data-lightbox]', () => import('./modules/gallery.js')],
  ['form[data-form]', () => import('./modules/forms.js')],
];

function boot() {
  initIcons();
  initHeader();
  initMobileMenu();
  initReveal();
  initBackToTop();

  lazyModules.forEach(([selector, load]) => {
    if (document.querySelector(selector)) {
      load().then((mod) => mod.default?.());
    }
  });
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', boot);
} else {
  boot();
}
