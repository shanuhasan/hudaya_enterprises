{{-- Not found: full site layout so visitors can navigate on. --}}
<x-layouts.app title="Page not found" description="The page you are looking for does not exist." robots="noindex">
  <main id="main">
    <section class="surface-ink section-y" aria-labelledby="nf-title" style="padding-top: calc(var(--header-h) + var(--section-y))">
      <div class="container-site">
        <div class="section-head max-w-2xl">
          <p class="eyebrow">Error 404</p>
          <h1 id="nf-title" class="t-h1">Page not <span class="t-em">found.</span></h1>
          <p class="t-lead">The page may have moved or no longer exists. Explore our products or send us an inquiry.</p>
          <div class="mt-4 flex flex-col gap-3 sm:flex-row">
            <a href="{{ route('products.index') }}" class="btn btn-primary">View Products <i data-lucide="arrow-right"></i></a>
            <a href="{{ route('contact') }}" class="btn btn-secondary">Contact Us</a>
          </div>
        </div>
      </div>
    </section>
  </main>
</x-layouts.app>
