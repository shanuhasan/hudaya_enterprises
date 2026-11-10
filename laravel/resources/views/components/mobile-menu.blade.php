{{--
  Component: mobile-menu — full-height drawer for phone & tablet (< lg).
  Behaviour: resources/js/modules/mobile-menu.js. Props: links (from header), navActive.
--}}
@props(['links' => [], 'navActive' => null])
<div id="mobile-menu" class="mobile-menu" data-menu hidden role="dialog" aria-modal="true" aria-label="Site menu">
  <div class="mobile-menu__scrim" data-menu-close aria-hidden="true"></div>

  <div class="mobile-menu__panel surface-ink" data-menu-panel>
    <div class="mobile-menu__pad mobile-menu__top">
      <x-logo />
      <button type="button" class="menu-toggle" data-menu-close>
        <i data-lucide="x"></i>
        <span class="sr-only">Close menu</span>
      </button>
    </div>

    <nav class="mobile-menu__pad mobile-menu__nav" aria-label="Mobile">
      <ul>
        @foreach ($links as $key => [$label, $href])
        <li><a class="mobile-menu__link" style="--i:{{ $loop->index }}" data-nav="{{ $key }}" @if ($navActive === $key) aria-current="page" @endif href="{{ $href }}"><span class="mobile-menu__index">{{ sprintf('%02d', $loop->iteration) }}</span>{{ $label }}</a></li>
        @endforeach
      </ul>
    </nav>

    <div class="mobile-menu__pad mobile-menu__footer">
      <a href="{{ route('contact') }}#quote" class="btn btn-primary btn-block">
        Request a Quote
        <i data-lucide="arrow-right"></i>
      </a>
      <div class="grid gap-3">
        @if ($site->phone_href)<a class="contact-line" href="tel:{{ $site->phone_href }}"><i data-lucide="phone"></i><span>{{ $site->phone ?: $site->phone_href }}</span></a>@endif
        @if ($site->email)<a class="contact-line" href="mailto:{{ $site->email }}"><i data-lucide="mail"></i><span>{{ $site->email }}</span></a>@endif
        @if ($site->whatsapp_url)
        <a class="contact-line" href="{{ $site->whatsapp_url }}" target="_blank" rel="noopener">
          @include('icons.brand.whatsapp')<span>Chat on WhatsApp<span class="sr-only"> (opens in a new tab)</span></span>
        </a>
        @endif
      </div>
    </div>
  </div>
</div>
