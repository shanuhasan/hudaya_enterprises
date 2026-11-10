{{--
  Component: logo — Hudaya Enterprises logo (light lettering for dark surfaces).
  Props: class (optional, e.g. "logo--lg")
  Source artwork: public/images/brand/logo.png (original colours) and logo-light.png (stacked).
--}}
@props(['class' => null])
<a href="{{ route('home') }}" class="logo {{ $class }}">
  <img src="{{ asset('images/brand/logo-lockup-light.png') }}" alt="{{ $site->name }} — Home" width="557" height="200" class="logo__img" decoding="async">
</a>
