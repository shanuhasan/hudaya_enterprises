<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Sign in · Admin · {{ $site->name }}</title>
  <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
  @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body>
  <div class="adm-login">
    <aside class="adm-login__brand" aria-label="{{ $site->name }}">
      <img src="{{ asset($site->logo ?: 'images/brand/logo-lockup-light.png') }}" alt="{{ $site->name }}" class="h-11 w-auto self-start">
      <div>
        <p class="adm-eyebrow">Admin panel</p>
        <p class="mt-4 max-w-md font-serif text-5xl leading-tight">{{ $site->tagline }}</p>
      </div>
      <p class="text-xs text-[#a5a5a0]">&copy; {{ date('Y') }} {{ $site->name }}</p>
    </aside>

    <main class="adm-login__panel" id="main">
      <div class="adm-login__box">
        <p class="adm-eyebrow">{{ $site->name }}</p>
        <h1 class="adm-title">Sign in</h1>
        <p class="adm-sub">Use your admin email and password.</p>

        @if (session('status'))
          <div class="adm-flash mt-8" role="status"><i data-lucide="circle-check"></i><p>{{ session('status') }}</p></div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}" class="adm-form-grid mt-8" novalidate>
          @csrf
          <x-admin.input name="email" label="Email" type="email" autocomplete="username" required autofocus />
          <x-admin.input name="password" label="Password" type="password" autocomplete="current-password" required />
          <label class="adm-check"><input type="checkbox" name="remember" value="1"> <span>Keep me signed in on this device</span></label>
          <button type="submit" class="adm-btn adm-btn--primary adm-btn--block mt-2">Sign in <i data-lucide="arrow-right"></i></button>
        </form>
      </div>
    </main>
  </div>
</body>
</html>
