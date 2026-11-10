{{-- Component: footer — company details come from $site (settings). --}}
<footer class="surface-ink pt-[var(--section-y-compact)]" aria-labelledby="footer-title">
  <h2 id="footer-title" class="sr-only">Site footer</h2>

  <div class="container-site">
    <div class="grid gap-12 md:grid-cols-2 lg:grid-cols-12 lg:gap-6">

      {{-- Brand --}}
      <div class="lg:col-span-4">
        <x-logo class="logo--lg" />
        <p class="t-small mt-8 max-w-sm">{{ $site->intro }}</p>
        @if ($site->whatsapp_url)
        <a href="{{ $site->whatsapp_url }}" target="_blank" rel="noopener" class="btn btn-secondary btn-sm mt-8">
          @include('icons.brand.whatsapp')
          Chat on WhatsApp<span class="sr-only"> (opens in a new tab)</span>
        </a>
        @endif
      </div>

      {{-- Products --}}
      <nav class="lg:col-span-2 lg:col-start-6" aria-label="Product categories">
        <h3 class="footer-heading">Products</h3>
        <ul class="footer-links">
          @foreach ($footerCategories as $category)
          <li><a href="{{ route('products.category', $category) }}">{{ trim($category->title.' '.$category->title_em) }}</a></li>
          @endforeach
          <li><a href="{{ route('products.index') }}">All Products</a></li>
          <li><a href="{{ route('contact') }}#quote">Private Label Inquiry</a></li>
        </ul>
      </nav>

      {{-- Company --}}
      <nav class="lg:col-span-2" aria-label="Company">
        <h3 class="footer-heading">Company</h3>
        <ul class="footer-links">
          <li><a href="{{ route('about') }}">About Us</a></li>
          <li><a href="{{ route('manufacturing') }}">Manufacturing</a></li>
          <li><a href="{{ route('quality') }}">Quality Assurance</a></li>
          <li><a href="{{ route('gallery') }}">Gallery</a></li>
          <li><a href="{{ route('contact') }}">Contact</a></li>
        </ul>
      </nav>

      {{-- Contact --}}
      <div class="lg:col-span-3">
        <h3 class="footer-heading">Contact</h3>
        <x-contact-lines />
      </div>
    </div>

    {{-- Bottom bar --}}
    <div class="mt-16 flex flex-col-reverse gap-6 border-t border-rule py-8 md:flex-row md:items-center md:justify-between">
      <div class="grid gap-2">
        <p class="t-small">&copy; {{ date('Y') }} {{ $site->name }}. All rights reserved.</p>
        @if ($site->photo_notice)<p class="t-small">{{ $site->photo_notice }}</p>@endif
      </div>

      <ul class="flex items-center gap-3">
        @if ($site->instagram)
        <li><a class="social-link" href="{{ $site->instagram }}" target="_blank" rel="noopener">@include('icons.brand.instagram')<span class="sr-only">Instagram (opens in a new tab)</span></a></li>
        @endif
        @if ($site->facebook)
        <li><a class="social-link" href="{{ $site->facebook }}" target="_blank" rel="noopener">@include('icons.brand.facebook')<span class="sr-only">Facebook (opens in a new tab)</span></a></li>
        @endif
        @if ($site->linkedin)
        <li><a class="social-link" href="{{ $site->linkedin }}" target="_blank" rel="noopener">@include('icons.brand.linkedin')<span class="sr-only">LinkedIn (opens in a new tab)</span></a></li>
        @endif
        @if ($site->whatsapp_profile_url)
        <li><a class="social-link" href="{{ $site->whatsapp_profile_url }}" target="_blank" rel="noopener">@include('icons.brand.whatsapp')<span class="sr-only">WhatsApp (opens in a new tab)</span></a></li>
        @endif
      </ul>
    </div>
  </div>
</footer>
