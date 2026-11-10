{{--
  Component: header — skip link, announcement bar, navbar and the mobile drawer.
  Props: navActive — key of the current page; that link gets aria-current="page".
--}}
@props(['navActive' => null])
@php
    $links = [
        'home' => ['Home', route('home')],
        'about' => ['About', route('about')],
        'products' => ['Products', route('products.index')],
        'manufacturing' => ['Manufacturing', route('manufacturing')],
        'quality' => ['Quality', route('quality')],
        'gallery' => ['Gallery', route('gallery')],
        'contact' => ['Contact', route('contact')],
    ];
@endphp
<a class="skip-link" href="#main">Skip to content</a>

<div class="site-top" data-site-header>
  @if ($site->announcement)
  <div class="announce" role="region" aria-label="Announcement">
    <div class="container-site announce__inner">
      <p class="announce__text">
        <span class="hidden md:inline">{{ $site->announcement }}</span>
        <span class="md:hidden">{{ $site->announcement_short }}</span>
      </p>
      <a href="{{ route('contact') }}#quote" class="announce__link">
        Request a quote
        <i data-lucide="arrow-right"></i>
      </a>
    </div>
  </div>
  @endif

  <header class="site-header">
    <div class="container-site site-header__inner">
      <x-logo />

      <nav class="site-nav" aria-label="Primary">
        <ul class="site-nav__list">
          @foreach ($links as $key => [$label, $href])
          <li><a class="nav-link" data-nav="{{ $key }}" @if ($navActive === $key) aria-current="page" @endif href="{{ $href }}">{{ $label }}</a></li>
          @endforeach
        </ul>
      </nav>

      <div class="site-header__actions">
        <a href="{{ route('contact') }}#quote" class="btn btn-primary btn-sm hidden md:inline-flex">
          Request a Quote
          <i data-lucide="arrow-right"></i>
        </a>
        <button type="button" class="menu-toggle" data-menu-open aria-controls="mobile-menu" aria-expanded="false">
          <i data-lucide="menu"></i>
          <span class="sr-only">Open menu</span>
        </button>
      </div>
    </div>
  </header>
</div>

<x-mobile-menu :links="$links" :nav-active="$navActive" />
