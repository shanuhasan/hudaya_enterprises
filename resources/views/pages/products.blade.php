<x-layouts.app :title="$page->meta_title" :description="$page->meta_description" nav-active="products">
  <main id="main">
    <x-page-hero :crumb="'Products'" :eyebrow="$page->hero_eyebrow" :title="$page->hero_title" :title-em="$page->hero_title_em" :lead="$page->hero_lead" :src="$page->hero_image" :alt="$page->hero_image_alt" :brief="'Products hero · black track pants and jacket flat-lay · dark backdrop · 16:9'" />

    {{-- ============================================================ INTRO --}}
    <section class="surface-bone section-y" aria-labelledby="products-intro-title">
      <div class="container-site grid gap-14 lg:grid-cols-12 lg:gap-6">
        <div class="lg:col-span-6" data-reveal>
          <p class="eyebrow">01 / Made to order</p>
          <h2 id="products-intro-title" class="t-h2 mt-8">Every product starts with <span class="t-em">your specification.</span></h2>
          <p class="t-lead mt-8">We do not sell ready stock. Each style is developed and produced for your brand, from fabric and fit to labels and packing.</p>
        </div>
        <div class="lg:col-span-5 lg:col-start-8" data-reveal>
          <dl class="spec-list">
            <div class="spec-list__row"><dt>Design</dt><dd>Your design, tech pack or a reference style</dd></div>
            <div class="spec-list__row"><dt>Fabric</dt><dd>Agreed with you during sampling</dd></div>
            <div class="spec-list__row"><dt>Sizing</dt><dd>Made to your size chart</dd></div>
            <div class="spec-list__row"><dt>Branding</dt><dd>Your labels, trims and finishing</dd></div>
            <div class="spec-list__row"><dt>Order types</dt><dd>Sample, bulk and repeat orders</dd></div>
          </dl>
        </div>
      </div>
    </section>

    {{-- ============================================================ CATEGORY SHOWCASES --}}
    <section class="surface-ink section-y" aria-labelledby="categories-title">
      <div class="container-site">
        <div class="section-head" data-reveal>
          <p class="eyebrow">02 / Categories</p>
          <h2 id="categories-title" class="t-h2">Explore the <span class="t-em">collections.</span></h2>
        </div>

        <div class="mt-16">
          @foreach ($categories as $category)
          <article @class(['showcase', 'showcase--reverse' => $loop->even])>
            <a href="{{ route('products.category', $category) }}" class="showcase__media category-tile" data-reveal tabindex="-1" aria-hidden="true">
              <x-media class="h-full category-tile__media" :src="$category->card_image" :alt="$category->card_image_alt" :brief="$category->name" />
            </a>
            <div class="showcase__body" data-reveal>
              <span class="showcase__index">{{ sprintf('%02d', $loop->iteration) }}</span>
              <h3 class="t-h2 mt-6">{{ $category->title }}@if ($category->title_em) <span class="t-em">{{ $category->title_em }}</span>@endif</h3>
              @if ($category->summary)<p class="t-body mt-6">{{ $category->summary }}</p>@endif
              @if ($category->types->isNotEmpty())
              <ul class="subtype-list mt-8">
                @foreach ($category->types as $type)<li>{{ $type->short_name }}</li>@endforeach
              </ul>
              @endif
              <div class="mt-10 flex flex-wrap gap-3">
                <a href="{{ route('products.category', $category) }}" class="btn btn-primary">View {{ $category->name }} <i data-lucide="arrow-right"></i></a>
                <a href="{{ route('contact', ['category' => $category->slug]) }}#quote" class="btn btn-secondary">Request a Quote</a>
              </div>
            </div>
          </article>
          @endforeach
        </div>
      </div>
    </section>

    {{-- ============================================================ CUSTOMISATION --}}
    <section class="surface-bone section-y" aria-labelledby="custom-title">
      <div class="container-site">
        <div class="grid gap-8 lg:grid-cols-12 lg:items-end">
          <div class="section-head lg:col-span-6" data-reveal>
            <p class="eyebrow">03 / Customisation</p>
            <h2 id="custom-title" class="t-h2">Details you can <span class="t-em">specify.</span></h2>
          </div>
          <p class="t-lead lg:col-span-4 lg:col-start-9" data-reveal>Tell us what you need. Feasibility of each option is confirmed with you during sampling.</p>
        </div>

        <ul class="feature-grid mt-16" data-reveal-group>
          <li class="feature" data-reveal>
            <span class="feature__num">i.</span>
            <h3 class="feature__title">Fabric &amp; weight</h3>
            <p class="feature__text">Choose fabric type, composition and weight to suit the product and price point.</p>
          </li>
          <li class="feature" data-reveal>
            <span class="feature__num">ii.</span>
            <h3 class="feature__title">Colours</h3>
            <p class="feature__text">Body, panel and trim colours matched to your collection.</p>
          </li>
          <li class="feature" data-reveal>
            <span class="feature__num">iii.</span>
            <h3 class="feature__title">Sizing &amp; fit</h3>
            <p class="feature__text">Garments made to your size chart, fit and grading requirements.</p>
          </li>
          <li class="feature" data-reveal>
            <span class="feature__num">iv.</span>
            <h3 class="feature__title">Branding &amp; labels</h3>
            <p class="feature__text">Your brand labels, care labels and tags for private label production.</p>
          </li>
          <li class="feature" data-reveal>
            <span class="feature__num">v.</span>
            <h3 class="feature__title">Trims &amp; details</h3>
            <p class="feature__text">Zips, drawcords, pockets, cuffs, panels and piping to your design.</p>
          </li>
          <li class="feature" data-reveal>
            <span class="feature__num">vi.</span>
            <h3 class="feature__title">Packing</h3>
            <p class="feature__text">Folding, tagging and packing prepared to your requirements.</p>
          </li>
        </ul>
      </div>
    </section>

    {{-- ============================================================ FEATURED --}}
    <section id="styles" class="surface-ink-2 section-y" aria-labelledby="featured-title" data-collection-filter>
      <div class="container-site">
        <div class="flex flex-col gap-10">
          <div class="section-head" data-reveal>
            <p class="eyebrow">04 / Featured</p>
            <h2 id="featured-title" class="t-h2">Featured <span class="t-em">styles.</span></h2>
          </div>
          <div class="collection-tools" data-reveal>
            <div class="filter-tabs" role="group" aria-label="Filter styles by category">
              <button type="button" class="filter-tab" data-filter="all" aria-pressed="true">All</button>
              @foreach ($categories as $category)
              <button type="button" class="filter-tab" data-filter="{{ $category->slug }}" aria-pressed="false">{{ $category->name }}</button>
              @endforeach
            </div>
            <x-collection-search :search-id="'products'" :search-label="'Search styles'" />
          </div>
        </div>
        <p class="sr-only" data-filter-status aria-live="polite"></p>
        <x-collection-empty />

        <div class="mt-14 grid grid-cols-2 gap-x-4 gap-y-12 md:gap-x-6 lg:grid-cols-4 lg:gap-y-16" data-reveal-group>
          @foreach ($featured as $product)
          <x-product-card :product="$product" :filter="$product->category->slug" />
          @endforeach
        </div>
      </div>
    </section>

    <x-cta-band :eyebrow="'Private label & bulk'" :title="'Have a design'" :title-em="'in mind?'" :text="'Send us your design, reference or tech pack with estimated quantities, and we will come back with sampling options and a quote.'" />
  </main>
</x-layouts.app>
