{{--
  Admin layout: black sidebar + off-white workspace.
  <x-admin.layout title="Products"> … </x-admin.layout>
--}}
@props(['title'])
@php
    $unread = \App\Models\Inquiry::query()->unread()->count();
    $nav = [
        ['admin.dashboard', 'admin.dashboard', 'layout-dashboard', 'Dashboard', null],
        ['admin.products.index', 'admin.products.*', 'package', 'Products', null],
        ['admin.categories.index', 'admin.categories.*|admin.types.*', 'folder-tree', 'Categories', null],
        ['admin.product-images.index', 'admin.product-images.*', 'image-plus', 'Product Images', null],
        ['admin.gallery.index', 'admin.gallery.*', 'images', 'Gallery', null],
        ['admin.inquiries.index', 'admin.inquiries.*', 'inbox', 'Inquiries', $unread ?: null],
    ];
    $isActive = fn (string $patterns) => request()->routeIs(...explode('|', $patterns));
    $user = auth()->user();
    $logo = $site->logo ?: 'images/brand/logo-lockup-light.png';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>{{ $title }} · Admin · {{ $site->name }}</title>
  <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
  @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body>
  <div class="adm-shell">
    <aside class="adm-sidebar" data-sidebar aria-label="Admin navigation">
      <div class="adm-sidebar__brand">
        <a href="{{ route('admin.dashboard') }}"><img src="{{ asset($logo) }}" alt="{{ $site->name }} — Dashboard"></a>
        <span>Admin panel</span>
      </div>

      <nav class="adm-nav">
        <p class="adm-nav__label">Manage</p>
        @foreach ($nav as [$route, $patterns, $icon, $label, $count])
          <a class="adm-nav__link" href="{{ route($route) }}" @if ($isActive($patterns)) aria-current="page" @endif>
            <i data-lucide="{{ $icon }}"></i>{{ $label }}
            @if ($count)<span class="adm-nav__count" title="{{ $count }} new">{{ $count }}</span>@endif
          </a>
        @endforeach

        <p class="adm-nav__label">Account</p>
        <a class="adm-nav__link" href="{{ route('admin.settings.edit') }}" @if ($isActive('admin.settings.*')) aria-current="page" @endif><i data-lucide="building-2"></i>Company Settings</a>
        <a class="adm-nav__link" href="{{ route('admin.profile.edit') }}" @if ($isActive('admin.profile.*')) aria-current="page" @endif><i data-lucide="user-round"></i>Admin Profile</a>
      </nav>

      <div class="adm-sidebar__foot">
        <a class="adm-nav__link" href="{{ route('home') }}" target="_blank" rel="noopener"><i data-lucide="external-link"></i>View website</a>
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit" class="adm-nav__link"><i data-lucide="log-out"></i>Log out</button>
        </form>
      </div>
    </aside>
    <div class="adm-scrim" data-sidebar-scrim hidden></div>

    <div class="adm-main">
      <header class="adm-topbar">
        <button type="button" class="adm-topbar__menu" data-sidebar-open aria-expanded="false"><i data-lucide="menu"></i><span class="sr-only">Open navigation</span></button>
        <div class="adm-topbar__user">
          <span class="hidden sm:inline">{{ $user->name }}</span>
          <span class="adm-avatar" aria-hidden="true">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</span>
        </div>
      </header>

      <main class="adm-content" id="main">
        <x-admin.flash />
        {{ $slot }}
      </main>
    </div>
  </div>
</body>
</html>
