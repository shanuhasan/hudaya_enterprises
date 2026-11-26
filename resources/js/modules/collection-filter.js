/**
 * Collection filter + search — category tabs and a text search that work together.
 *
 * Markup (all inside one [data-collection-filter] root):
 *   <button data-filter="all" aria-pressed="true">All</button>
 *   <input type="search" data-collection-search>        (optional)
 *   <button data-search-clear hidden>                   (optional)
 *   <p data-filter-status aria-live="polite" class="sr-only"></p>
 *   <div data-filter-empty hidden> … <span data-empty-query></span> … <button data-filter-reset> </div>
 *   <article data-filter-item data-category="mens sports" data-search="name type …">
 *
 * - data-category may hold several space-separated keys.
 * - Search matches every word typed (any order, from the start of a word) against
 *   data-search (or the item text).
 * - Links elsewhere can pre-select a tab: <a href="#styles" data-filter-link="mens">.
 * - Esc in the search box clears it.
 */
const DEBOUNCE_MS = 120;

const normalise = (text) =>
  (text || '')
    .toLowerCase()
    .normalize('NFD')
    .replace(/[̀-ͯ]/g, '')
    .replace(/[’']/g, '')
    .replace(/[^a-z0-9\s-]/g, ' ');

export default function initCollectionFilter() {
  document.querySelectorAll('[data-collection-filter]').forEach(setup);

  document.querySelectorAll('[data-filter-link]').forEach((link) => {
    link.addEventListener('click', () => {
      const target = document.querySelector(link.getAttribute('href'));
      const root = target?.closest('[data-collection-filter]') || target?.querySelector('[data-collection-filter]');
      root?.dispatchEvent(new CustomEvent('collection:filter', { detail: link.dataset.filterLink }));
    });
  });
}

function setup(root) {
  const buttons = [...root.querySelectorAll('[data-filter]')];
  const items = [...root.querySelectorAll('[data-filter-item]')];
  const search = root.querySelector('[data-collection-search]');
  const clear = root.querySelector('[data-search-clear]');
  const status = root.querySelector('[data-filter-status]');
  const empty = root.querySelector('[data-filter-empty]');
  const emptyQuery = root.querySelector('[data-empty-query]');

  // Pre-compute searchable words once. Matching is by word start, so "mens"
  // finds "Men's" but not "Women's", and "jog" finds "Jogger".
  const index = new Map(
    items.map((item) => [item, normalise(item.dataset.search || item.textContent).split(/[\s-]+/).filter(Boolean)]),
  );

  const state = { filter: 'all', query: '' };
  let debounce = null;

  function apply() {
    const words = normalise(state.query).split(/\s+/).filter(Boolean);
    let shown = 0;

    buttons.forEach((btn) => btn.setAttribute('aria-pressed', String(btn.dataset.filter === state.filter)));

    items.forEach((item) => {
      const keys = (item.dataset.category || '').split(/\s+/);
      const inFilter = state.filter === 'all' || keys.includes(state.filter);
      const itemWords = index.get(item);
      const inSearch = words.every((word) => itemWords.some((w) => w.startsWith(word)));
      const match = inFilter && inSearch;
      const wasHidden = item.hidden;

      item.hidden = !match;
      if (!match) return;

      shown += 1;
      item.classList.add('is-visible'); // items hidden while scrolling past never got their reveal class
      if (wasHidden) {
        item.classList.remove('filter-in');
        void item.offsetWidth; // restart the entrance animation
        item.classList.add('filter-in');
      }
    });

    if (clear) clear.hidden = !state.query;
    if (empty) {
      empty.hidden = shown > 0;
      if (emptyQuery) emptyQuery.textContent = state.query ? `“${state.query}”` : 'this selection';
    }
    if (status) {
      status.textContent = shown
        ? `${shown} ${shown === 1 ? 'style' : 'styles'} shown`
        : 'No styles match. Try another search or category.';
    }
  }

  const setFilter = (value) => { state.filter = value; apply(); };
  const setQuery = (value) => { state.query = value.trim(); apply(); };

  buttons.forEach((btn) => btn.addEventListener('click', () => setFilter(btn.dataset.filter)));
  root.addEventListener('collection:filter', (event) => setFilter(event.detail));

  if (search) {
    search.addEventListener('input', () => {
      clearTimeout(debounce);
      debounce = setTimeout(() => setQuery(search.value), DEBOUNCE_MS);
    });
    search.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && search.value) {
        event.preventDefault();
        search.value = '';
        setQuery('');
      }
    });
    // Enter shouldn't submit anything — the results are already live.
    search.form?.addEventListener('submit', (event) => event.preventDefault());
  }

  clear?.addEventListener('click', () => {
    search.value = '';
    setQuery('');
    search.focus();
  });

  root.querySelectorAll('[data-filter-reset]').forEach((btn) =>
    btn.addEventListener('click', () => {
      if (search) search.value = '';
      state.query = '';
      setFilter('all');
      (search || buttons[0])?.focus();
    }),
  );
}
