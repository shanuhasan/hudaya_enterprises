/**
 * Inquiry form — validation, pre-fill and (static phase) hand-off.
 *
 * Markup:
 *   <form data-form="inquiry" data-email="…" data-whatsapp="…" novalidate>
 *     <div class="field"><input required data-error="…"> <label> <p class="field__error"></p></div>
 *   </form>
 *   <div data-form-success hidden> … [data-send-email] [data-send-whatsapp] [data-form-edit] </div>
 *
 * - Pre-fills product / category from the URL: contact.html?product=Tapered%20Jogger&category=lowers
 * - Validates on submit, then live on input; errors are announced and linked with aria-describedby.
 * - Phase 1 (static): a valid inquiry is turned into a ready-to-send email / WhatsApp message.
 *   Phase 2 (Laravel): the form POSTs to the server instead — markup stays the same.
 */
const FIELD_LABELS = {
  name: 'Name',
  company: 'Company',
  email: 'Email',
  phone: 'Phone / WhatsApp',
  country: 'Country',
  city: 'City',
  buyer_type: 'Business type',
  category: 'Product category',
  product: 'Product / style',
  quantity: 'Estimated quantity',
  timeline: 'Target timeline',
  message: 'Requirements',
};

// Config values in [brackets] are unfilled placeholders — treat them as missing.
const isSet = (value) => Boolean(value) && !value.trim().startsWith('[');

export default function initForms() {
  document.querySelectorAll('form[data-form="inquiry"]').forEach(setupForm);
}

function setupForm(form) {
  const success = document.querySelector('[data-form-success]');
  let submitted = false;

  prefill(form);

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
    event.preventDefault();
    submitted = true;

    const invalid = fields.filter((el) => !validate(el));
    if (invalid.length) {
      const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      invalid[0].focus({ preventScroll: true });
      invalid[0].scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'center' });
      return;
    }

    // Honeypot: bots fill hidden fields — silently ignore.
    if (form.querySelector('[name="website"]')?.value) return;

    showSuccess(form, success);
  });

  success?.querySelector('[data-form-edit]')?.addEventListener('click', () => {
    success.hidden = true;
    form.hidden = false;
    form.querySelector('input, select, textarea')?.focus();
  });
}

function prefill(form) {
  const params = new URLSearchParams(window.location.search);
  const product = params.get('product');
  const category = params.get('category');

  if (product && form.elements.product) form.elements.product.value = product.slice(0, 120);
  if (category && form.elements.category) {
    const option = [...form.elements.category.options].find((o) => o.value === category);
    if (option) form.elements.category.value = category;
  }
}

function summarise(form) {
  const data = new FormData(form);
  const lines = ['New manufacturing inquiry', ''];

  Object.entries(FIELD_LABELS).forEach(([key, label]) => {
    const el = form.elements[key];
    let value = data.get(key);
    if (el?.tagName === 'SELECT' && value) value = el.options[el.selectedIndex].text;
    if (value) lines.push(`${label}: ${value}`);
  });

  const custom = data.getAll('customisation[]');
  if (custom.length) lines.push(`Customisation: ${custom.join(', ')}`);
  return lines.join('\n');
}

function showSuccess(form, success) {
  if (!success) return;
  const body = summarise(form);
  const email = form.dataset.email || '';
  const whatsapp = (form.dataset.whatsapp || '').replace(/\D/g, '');

  const emailLink = success.querySelector('[data-send-email]');
  const waLink = success.querySelector('[data-send-whatsapp]');

  if (emailLink) {
    emailLink.hidden = !isSet(email);
    emailLink.href = `mailto:${email}?subject=${encodeURIComponent('Manufacturing inquiry')}&body=${encodeURIComponent(body)}`;
  }
  if (waLink) {
    waLink.hidden = !whatsapp;
    waLink.href = `https://wa.me/${whatsapp}?text=${encodeURIComponent(body)}`;
    waLink.target = '_blank';
    waLink.rel = 'noopener';
  }

  const notice = success.querySelector('[data-send-missing]');
  if (notice) notice.hidden = isSet(email) || Boolean(whatsapp);

  form.hidden = true;
  success.hidden = false;
  success.focus();
  success.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
