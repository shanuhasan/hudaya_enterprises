{{--
  Component: logo — two versions, CSS shows the right one for the background:
    on light surfaces → original colours (dark lettering)   setting "logo",         default logo-lockup.png
    on dark surfaces  → white lettering                      setting "logo_on_dark", default logo-lockup-light.png
  Dark contexts: .surface-dark, .surface-charcoal and the transparent header over a photo hero
  (see the LOGO rules in resources/css/components.css). Uploads: Admin → Company settings.
  Props: class (optional, e.g. "logo--lg")
--}}
@props(['class' => null])
@php
    $onLight = $site->logo ?: null;
    $onDark = $site->logo_on_dark ?: null;
@endphp
<a href="{{ route('home') }}" class="logo {{ $class }}">
  <img src="{{ asset($onLight ?? 'images/brand/logo-lockup.png') }}" alt="{{ $site->name }} — Home" @unless ($onLight) width="557" height="200" @endunless class="logo__img logo__img--on-light" decoding="async">
  <img src="{{ asset($onDark ?? 'images/brand/logo-lockup-light.png') }}" alt="{{ $site->name }} — Home" @unless ($onDark) width="557" height="200" @endunless class="logo__img logo__img--on-dark" decoding="async">
</a>
