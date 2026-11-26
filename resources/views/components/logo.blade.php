{{--
  Component: logo — light version for dark surfaces.
  Uses the logo uploaded in Admin → Company settings, otherwise the supplied Hudaya logo
  (public/images/brand/logo-lockup-light.png; originals: logo.png, logo-light.png).
  Props: class (optional, e.g. "logo--lg")
--}}
@props(['class' => null])
<a href="{{ route('home') }}" class="logo {{ $class }}">
  @if ($site->logo)
  <img src="{{ asset($site->logo) }}" alt="{{ $site->name }} — Home" class="logo__img" decoding="async">
  @else
  <img src="{{ asset('images/brand/logo-lockup-light.png') }}" alt="{{ $site->name }} — Home" width="557" height="200" class="logo__img" decoding="async">
  @endif
</a>
