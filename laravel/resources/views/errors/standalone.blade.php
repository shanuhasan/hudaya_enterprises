{{--
  Error page that works even when the database or the asset build is unavailable (500, 503…):
  no queries, no Vite, inline styles in the site palette. Never shows technical details.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex">
  <title>{{ $title }} | {{ config('app.name') }}</title>
  <style>
    html,body{margin:0;height:100%;background:#080808;color:#F5F5F2;font:16px/1.7 "Inter",system-ui,-apple-system,"Segoe UI",sans-serif}
    main{min-height:100%;display:grid;place-items:center;padding:2rem 1.25rem;box-sizing:border-box}
    .box{max-width:34rem}
    .code{color:#A88B60;letter-spacing:.2em;font-size:.75rem;text-transform:uppercase}
    h1{font-family:"Cormorant Garamond",Georgia,serif;font-weight:500;font-size:clamp(2.5rem,6vw,4rem);line-height:1.05;margin:1rem 0}
    p{color:#d6d6d2;margin:0 0 2rem}
    a{display:inline-block;padding:.9rem 1.5rem;background:#fff;color:#080808;text-decoration:none;font-size:.8rem;letter-spacing:.14em;text-transform:uppercase;font-weight:600}
    a:focus-visible{outline:2px solid #A88B60;outline-offset:3px}
  </style>
</head>
<body>
  <main>
    <div class="box">
      <p class="code">Error {{ $code }}</p>
      <h1>{{ $title }}</h1>
      <p>{{ $text }}</p>
      <a href="{{ $href ?? url('/') }}">{{ $cta ?? 'Back to the homepage' }}</a>
    </div>
  </main>
</body>
</html>
