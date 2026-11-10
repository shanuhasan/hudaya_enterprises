/**
 * Inquiry form — browser-side validation for the quote form on /contact.
 *
 * Markup:
 *   <form data-form="inquiry" action="/contact" method="post" novalidate>
 *     <div class="field"><input required data-error="…"> <label> <p class="field__error"></p></div>
 *     <button type="submit" data-submit>
 *   </form>
 *
 * - Checks fields when the visitor leaves them, re-checks live once an error shows, checks all on submit.
 * - A valid form is POSTed to Laravel (StoreInquiryRequest validates again on the server).
 * - Server-side errors arrive in the markup (aria-invalid) and are focused on load.
 * - Product / category pre-fill from ?product=…&category=… is done by the server.
 */
export default function initForms() {
  document.querySelectorAll('form[data-form="inquiry"]').forEach(setupForm);
}

function setupForm(form) {
  let submitted = false;

  const fields = [...form.querySelectorAll('input, select, textarea')].filter((el) => el.name);

  function errorFor(el) {
    return el.closest('.field')?.querySelector('.field__error');
  }

  function validate(el) {
    const wrapper = el.closest('.field');
    const error = errorFor(el);
    if (!wrapper || !error) return true;

    const valid = el.checkValidity();
    wrapper.classList.toggle('is-invalid', !valid);
    el.setAttribute('aria-invalid', String(!valid));
    error.textContent = valid ? '' : el.dataset.error || el.validationMessage;
    if (!error.id) error.id = `${el.id || el.name}-error`;
    const describedBy = new Set((el.getAttribute('aria-describedby') || '').split(' ').filter(Boolean));
    describedBy.add(error.id);
    el.setAttribute('aria-describedby', [...describedBy].join(' '));
    return valid;
  }

  // Validation timing (calm, not nagging):
  // - a field is checked when the visitor leaves it after typing something,
  // - once it shows an error, it re-checks live so the error clears as soon as it's fixed,
  // - on submit, every field is checked.
  const touched = new WeakSet();
  fields.forEach((el) => {
    el.addEventListener('blur', () => {
      if (el.value || touched.has(el)) {
        touched.add(el);
        validate(el);
      }
    });
    const live = () => {
      if (submitted || el.getAttribute('aria-invalid') === 'true') validate(el);
    };
    el.addEventListener('input', live);
    el.addEventListener('change', live);
  });

  // Character counters: <p data-count-for="field-id"></p>
  form.querySelectorAll('[data-count-for]').forEach((counter) => {
    const el = form.querySelector(`#${counter.dataset.countFor}`);
    if (!el?.maxLength || el.maxLength < 0) return;
    const update = () => { counter.textContent = `${el.value.length} / ${el.maxLength}`; };
    el.addEventListener('input', update);
    update();
  });

  form.addEventListener('submit', (event) => {
    submitted = true;

    const invalid = fields.filter((el) => !validate(el));
    if (invalid.length) {
      event.preventDefault();
      const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      invalid[0].focus({ preventScroll: true });
      invalid[0].scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'center' });
      return;
    }

    // Valid: let the browser POST it to Laravel. Block double submits while it is sending.
    const button = form.querySelector('[data-submit]');
    if (button) {
      if (button.disabled) { event.preventDefault(); return; }
      button.disabled = true;
      button.setAttribute('aria-busy', 'true');
    }
  });

  // Server-side errors are already in the markup: link them for screen readers and focus the first one.
  const serverInvalid = fields.filter((el) => el.getAttribute('aria-invalid') === 'true');
  serverInvalid.forEach((el) => touched.add(el));
  serverInvalid[0]?.focus({ preventScroll: true });
}
