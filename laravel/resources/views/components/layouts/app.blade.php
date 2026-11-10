{{--
  Layout: every public page.
  Usage:
    <x-layouts.app title="About Us" description="…" nav-active="about">
      <main id="main"> … </main>
    </x-layouts.app>
  Props:
    title, description   page <title> / meta description (≤ 160 chars)       (required)
    robots               e.g. "noindex, nofollow"                             (optional)
    navActive            home | about | products | manufacturing | quality | gallery | contact
    hideWhatsapp         hide the floating WhatsApp button (contact page has its own)
    header               "overlay" = transparent header over a full-bleed hero (default)
--}}
@props([
    'title',
    'description',
    'robots' => null,
    'navActive' => null,
    'hideWhatsapp' => false,
    'header' => 'overlay',
])
@php
    // Structured data: only facts that are actually set (nothing invented).
    $organization = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $site->name,
        'description' => $site->intro,
        'url' => url('/').'/',
        'logo' => asset('images/brand/logo.png'),
        'email' => $site->email ?: null,
        'telephone' => $site->phone ?: null,
        'address' => $site->address ?: null,
    ]);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title }} | {{ $site->name }}</title>
  <meta name="description" content="{{ $description }}">
  @if ($robots)<meta name="robots" content="{{ $robots }}">@endif
  <meta name="theme-color" content="#080808">
  <link rel="canonical" href="{{ url()->current() }}">

  <meta property="og:type" content="website">
  <meta property="og:locale" content="en_IN">
  <meta property="og:site_name" content="{{ $site->name }}">
  <meta property="og:title" content="{{ $title }} | {{ $site->name }}">
  <meta property="og:description" content="{{ $description }}">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="{{ asset('images/brand/og-image.png') }}">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="{{ $site->name }} — {{ $site->tagline }}">
  <meta name="twitter:card" content="summary_large_image">

  <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png" sizes="32x32">
  <link rel="icon" href="{{ asset('images/brand/icon-192.png') }}" type="image/png" sizes="192x192">
  <link rel="apple-touch-icon" href="{{ asset('images/brand/apple-touch-icon.png') }}">

  {{-- Fonts are self-hosted via @fontsource (see resources/css/app.css). --}}
  <script>document.documentElement.classList.add('js');</script>
  @vite(['resources/css/app.css', 'resources/js/main.js'])

  <script type="application/ld+json">{!! json_encode($organization, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_PRETTY_PRINT) !!}</script>
</head>
<body class="surface-ink" data-header="{{ $header }}">
  <x-header :nav-active="$navActive" />

  {{ $slot }}

  <x-footer />
  <x-floating-actions :hide-whatsapp="$hideWhatsapp" />
</body>
</html>
