{{--
  Component: floating-actions — back-to-top + WhatsApp (bottom right).
  Props: hideWhatsapp — true on the contact page, which has its own WhatsApp option.
  Behaviour: resources/js/modules/back-to-top.js and header.js (scroll thresholds).
--}}
@props(['hideWhatsapp' => false])
<aside class="floating-actions" aria-label="Quick actions">
  <button type="button" class="fab fab--top" data-back-to-top>
    <i data-lucide="arrow-up"></i>
    <span class="fab__label" aria-hidden="true">Back to top</span>
    <span class="sr-only">Back to top</span>
  </button>
  @if ($site->whatsapp_url && ! $hideWhatsapp)
  <a class="fab fab--whatsapp" href="{{ $site->whatsapp_url }}" target="_blank" rel="noopener">
    @include('icons.brand.whatsapp')
    <span class="fab__label" aria-hidden="true">Chat on WhatsApp</span>
    <span class="sr-only">Chat with {{ $site->name }} on WhatsApp (opens in a new tab)</span>
  </a>
  @endif
</aside>
