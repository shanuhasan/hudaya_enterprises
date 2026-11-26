/**
 * Enhanced dropdowns (Chosen-style) with Tom Select — shared by the website and the admin.
 *
 *   enhanceSelects('select.field__input', { labelAsPlaceholder: true })   // public form
 *   enhanceSelects('select.adm-select')                                   // admin
 *
 * - Short lists open straight to their options; lists of SEARCH_FROM+ options get a search
 *   box at the top of the dropdown (like Chosen's search threshold).
 * - The original <select> stays in the form (hidden) and keeps the value, so form submission,
 *   server validation, `required`, data-autosubmit and resources/js/modules/forms.js work as before.
 * - An empty first option ("Select…", "All categories", "Not sure yet") becomes the placeholder;
 *   it stays selectable when it is not disabled (e.g. "All categories" in admin filters).
 * - Without JavaScript the native <select> is shown — nothing breaks.
 * Styles: resources/css/components.css (public) and resources/css/admin.css (admin).
 */
import TomSelect from 'tom-select/base';
import dropdownInput from 'tom-select/plugins/dropdown_input/plugin.js';

TomSelect.define('dropdown_input', dropdownInput);

const SEARCH_FROM = 7;

/**
 * @param {string} selector
 * @param {{ labelAsPlaceholder?: boolean }} options
 *   labelAsPlaceholder — the field's floating label acts as the placeholder (public form),
 *   so no placeholder text is drawn inside the control.
 */
export function enhanceSelects(selector, { labelAsPlaceholder = false } = {}) {
  document.querySelectorAll(selector).forEach((select) => {
    if (select.tomselect || select.multiple) return;

    const empty = select.querySelector('option[value=""]');
    const choices = [...select.options].filter((o) => o.value !== '').length;
    const searchable = choices >= SEARCH_FROM;
    const field = select.closest('.field, .adm-field');

    const ts = new TomSelect(select, {
      allowEmptyOption: Boolean(empty && !empty.disabled),
      placeholder: labelAsPlaceholder ? '' : (empty?.textContent.trim() ?? ''),
      controlInput: searchable ? undefined : null, // no typing in the closed control
      plugins: searchable ? ['dropdown_input'] : [],
      maxOptions: null,
      create: false,
      searchField: ['text'],
      render: {
        no_results: () => '<div class="no-results">No matches</div>',
      },
      // forms.js listens for "blur" on the original select to validate the field.
      onBlur: () => select.dispatchEvent(new Event('blur')),
      onChange: () => field?.classList.toggle('is-filled', ts.getValue() !== ''),
    });

    // Accessibility: Tom Select moves the <label for> to its own control, so the hidden
    // original <select> keeps a name of its own, and its internal placeholder input is hidden
    // from assistive technology (it is never focused or typed into).
    const labelText = (ts.control_input?.getAttribute('aria-label'))
      || document.querySelector(`label[for="${ts.control_input?.id || ''}"], label[for="${select.id}-ts-control"]`)?.textContent
      || field?.querySelector('label')?.textContent
      || '';
    if (labelText.trim()) select.setAttribute('aria-label', labelText.replace(/\s+/g, ' ').replace(/\(optional\)/i, '').trim());
    ts.wrapper.querySelectorAll('.items-placeholder').forEach((el) => el.setAttribute('aria-hidden', 'true'));

    if (searchable) {
      const search = ts.dropdown.querySelector('.dropdown-input');
      search?.setAttribute('placeholder', 'Type to search…');
      search?.setAttribute('aria-label', 'Search options');
    }
    field?.classList.add('is-enhanced');
    field?.classList.toggle('is-filled', ts.getValue() !== '');
  });
}

export default function init() {
  enhanceSelects('select.field__input', { labelAsPlaceholder: true });
}
