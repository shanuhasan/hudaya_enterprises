<x-layouts.app :title="$page->meta_title" :description="$page->meta_description" nav-active="home">
  <main id="main">

    {{-- ============================================================ 3. HERO --}}
    <section class="hero surface-ink" aria-labelledby="hero-title">
      <div class="hero__media" aria-hidden="true">
        <x-media :src="$page->hero_image" :alt="$page->hero_image_alt" brief="Home hero image" img-class="hero-settle" loading="eager" priority="high" />
      </div>
      <div class="hero__overlay" aria-hidden="true"></div>

      <div class="container-site hero__inner">
        <div class="hero__content">
          <p class="eyebrow" data-reveal>{{ $page->hero_eyebrow }}</p>
          <h1 id="hero-title" class="t-display mt-8" data-reveal>
            {{ $page->hero_title }} <span class="t-em">{{ $page->hero_title_em }}</span>@if ($page->hero_title_line2)<br>
            {{ $page->hero_title_line2 }}@endif
          </h1>
          <div class="mt-10 grid gap-10 lg:grid-cols-12 lg:items-end">
            <p class="hero__lead lg:col-span-5" data-reveal>
              {{ $page->hero_lead }}
            </p>
            <div class="flex flex-col gap-3 sm:flex-row lg:col-span-6 lg:col-start-7 lg:justify-end" data-reveal>
              <a href="{{ route('products.index') }}" class="btn btn-primary">
                Explore Collection
                <i data-lucide="arrow-right"></i>
              </a>
              <a href="{{ route('contact') }}#quote" class="btn btn-secondary">Request a Quote</a>
            </div>
          </div>
        </div>

        <nav class="hero__strip" aria-label="Collections">
          @foreach ($categories as $category)
          <a href="{{ route('products.category', $category) }}" class="hero__strip-item">
            <span><span class="hero__strip-index">{{ sprintf('%02d', $loop->iteration) }}</span>{{ trim($category->title.' '.$category->title_em) }}</span>
            <i data-lucide="arrow-up-right"></i>
          </a>
          @endforeach
          <a href="{{ route('contact') }}#quote" class="hero__strip-item">
            <span><span class="hero__strip-index">{{ sprintf('%02d', $categories->count() + 1) }}</span>Private Label &amp; Bulk</span>
            <i data-lucide="arrow-up-right"></i>
          </a>
          <a href="#about" class="scroll-cue">Scroll <i data-lucide="arrow-down"></i></a>
        </nav>
      </div>
    </section>

    {{-- ============================================================ 4. COMPANY INTRODUCTION --}}
    <section id="about" class="surface-bone section-y" aria-labelledby="intro-title">
      <div class="container-site">
        <div class="grid gap-16 lg:grid-cols-12 lg:gap-6">
          <div class="lg:col-span-6">
            <p class="eyebrow" data-reveal>01 / About Hudaya</p>
            <h2 id="intro-title" class="t-h2 mt-8" data-reveal>
              A manufacturing partner for brands that care how their garments are <span class="t-em">made.</span>
            </h2>
            <div class="mt-12 grid gap-8 md:grid-cols-2 lg:mt-16" data-reveal>
              <p class="t-body">
                {{ $site->name }} manufactures lowers, track pants and jackets for clothing brands, wholesalers,
                retailers and private label businesses.
              </p>
              <p class="t-body">
                We work from your designs and requirements — developing samples, agreeing specifications and
                producing bulk orders with attention to fabric, fit and finish.
              </p>
            </div>
            <a href="{{ route('about') }}" class="link-arrow mt-10" data-reveal>
              About the company
              <i data-lucide="arrow-right"></i>
            </a>
          </div>

          <div class="lg:col-span-5 lg:col-start-8" data-reveal>
            <div class="image-pair">
              <x-media :class="'image-pair__main'" :src="'images/placeholders/details/stitching-closeup.jpg'" :alt="'Close-up of stitching along a black garment seam'" :brief="'Stitching close-up on a black garment seam · 4:5'" />
              <x-media :class="'image-pair__inset'" :src="'images/placeholders/details/fabric-texture.jpg'" :alt="'Folded black fabric showing its texture'" :brief="'Folded fabric texture · 1:1'" />
            </div>
          </div>
        </div>

        <div class="mt-20 grid gap-6 border-t border-rule pt-10 lg:mt-28 lg:grid-cols-12" data-reveal>
          <p class="t-meta lg:col-span-2 lg:pt-3">We work with</p>
          <ul class="audience-list lg:col-span-10">
            <li>Clothing brands</li>
            <li>Wholesalers</li>
            <li>Retailers</li>
            <li>Fashion businesses</li>
            <li><span class="t-em">Private label</span> brands</li>
            <li>Bulk buyers</li>
          </ul>
        </div>
      </div>
    </section>

    {{-- ============================================================ 5. CATEGORY SHOWCASE --}}
    <section class="surface-ink section-y" aria-labelledby="collections-title">
      <div class="container-site">
        <div class="grid gap-8 lg:grid-cols-12 lg:items-end">
          <div class="section-head lg:col-span-6" data-reveal>
            <p class="eyebrow">02 / Collections</p>
            <h2 id="collections-title" class="t-h2">Two categories. <span class="t-em">One standard.</span></h2>
          </div>
          <p class="t-lead lg:col-span-4 lg:col-start-9" data-reveal>
            We focus on lowers and jackets, so every pattern, fabric choice and finishing detail gets specialist attention.
          </p>
        </div>

        <div class="mt-16 grid gap-16 lg:grid-cols-12 lg:gap-6">
          @foreach ($categories as $category)
          {{-- Staggered layout: wide tile, then a narrower one set lower — repeating. --}}
          <div class="{{ $loop->odd ? 'lg:col-span-7' : 'lg:col-span-5 lg:mt-40' }}" data-reveal>
            <a href="{{ route('products.category', $category) }}" class="category-tile">
              <x-media class="category-tile__media" :src="$category->card_image" :alt="$category->card_image_alt" :brief="$category->name" />
              <div class="media-overlay z-[1]" aria-hidden="true"></div>
              <div class="category-tile__content">
                <span class="category-tile__index">{{ sprintf('%02d', $loop->iteration) }} / Collection</span>
                <h3 class="category-tile__title">{{ $category->title }}@if ($category->title_em) <span class="t-em">{{ $category->title_em }}</span>@endif</h3>
                <span class="link-arrow">View collection <i data-lucide="arrow-right"></i></span>
              </div>
            </a>
            @if ($category->highlights)
            <ul class="subtype-list mt-6">
              @foreach ($category->highlights as $highlight)<li>{{ $highlight }}</li>@endforeach
            </ul>
            @endif
          </div>
          @endforeach
        </div>
      </div>
    </section>

    {{-- ============================================================ 6. FEATURED PRODUCTS --}}
    <section class="surface-ink-2 section-y" aria-labelledby="featured-title" data-collection-filter>
      <div class="container-site">
        <div class="flex flex-col gap-10 lg:flex-row lg:items-end lg:justify-between">
          <div class="section-head" data-reveal>
            <p class="eyebrow">03 / Featured</p>
            <h2 id="featured-title" class="t-h2">Featured <span class="t-em">styles.</span></h2>
            <p class="t-lead">A selection of the styles we manufacture. Each can be developed to your fabric, colour and branding requirements.</p>
          </div>
          <div class="filter-tabs" role="group" aria-label="Filter styles by category" data-reveal>
            <button type="button" class="filter-tab" data-filter="all" aria-pressed="true">All</button>
            @foreach ($featured->pluck('category')->unique('id')->sortBy('sort_order') as $category)
            <button type="button" class="filter-tab" data-filter="{{ $category->slug }}" aria-pressed="false">{{ $category->name }}</button>
            @endforeach
          </div>
        </div>
        <p class="sr-only" data-filter-status aria-live="polite"></p>

        <div class="mt-14 grid grid-cols-2 gap-x-4 gap-y-12 md:gap-x-6 lg:grid-cols-4 lg:gap-y-16" data-reveal-group>
          @foreach ($featured as $product)
          <x-product-card :product="$product" :filter="$product->category->slug" />
          @endforeach
        </div>

        <div class="mt-16 flex justify-center" data-reveal>
          <a href="{{ route('products.index') }}" class="btn btn-secondary">
            View All Products
            <i data-lucide="arrow-right"></i>
          </a>
        </div>
      </div>
    </section>

    {{-- ============================================================ 7. CAPABILITIES --}}
    {{-- Confirm this list with the client before launch — no service is listed that is not offered. --}}
    <section class="surface-bone section-y" aria-labelledby="capabilities-title">
      <div class="container-site grid gap-16 lg:grid-cols-12 lg:gap-6">
        <div class="lg:col-span-5">
          <div class="lg:sticky lg:top-32">
            <div class="section-head" data-reveal>
              <p class="eyebrow">04 / Capabilities</p>
              <h2 id="capabilities-title" class="t-h2">Built around <span class="t-em">your</span> brand.</h2>
              <p class="t-lead">From a first sample to repeat bulk orders, each stage is handled with the same care.</p>
            </div>
            <div class="mt-12 hidden lg:block" data-reveal>
              <x-media :class="'aspect-[4/3]'" :src="'images/placeholders/manufacturing/sewing-machine.jpg'" :alt="'Industrial sewing machine stitching dark fabric'" :brief="'Industrial sewing machine · dark fabric · 4:3'" />
            </div>
          </div>
        </div>

        <div class="lg:col-span-6 lg:col-start-7">
          <ol class="index-list" data-reveal-group>
            <li class="index-list__item" data-reveal>
              <span class="index-list__num">01</span>
              <h3 class="index-list__title">Private label manufacturing</h3>
              <p class="index-list__text">Garments produced to your specifications and finished with your own branding.</p>
            </li>
            <li class="index-list__item" data-reveal>
              <span class="index-list__num">02</span>
              <h3 class="index-list__title">Sampling &amp; development</h3>
              <p class="index-list__text">Samples are developed from your designs or references so fit and finish are agreed before production.</p>
            </li>
            <li class="index-list__item" data-reveal>
              <span class="index-list__num">03</span>
              <h3 class="index-list__title">Cutting &amp; stitching</h3>
              <p class="index-list__text">Patterns are cut and garments assembled with attention to seam strength and consistency.</p>
            </li>
            <li class="index-list__item" data-reveal>
              <span class="index-list__num">04</span>
              <h3 class="index-list__title">Finishing &amp; packing</h3>
              <p class="index-list__text">Trims, final finishing and packing prepared to your requirements.</p>
            </li>
            <li class="index-list__item" data-reveal>
              <span class="index-list__num">05</span>
              <h3 class="index-list__title">Bulk production</h3>
              <p class="index-list__text">Approved styles produced in bulk for brands, wholesalers and retailers.</p>
            </li>
          </ol>
          <a href="{{ route('manufacturing') }}" class="link-arrow mt-12" data-reveal>
            Explore manufacturing
            <i data-lucide="arrow-right"></i>
          </a>
        </div>
      </div>
    </section>

    {{-- ============================================================ 8. PROCESS --}}
    <section class="surface-ink section-y" aria-labelledby="process-title">
      <div class="container-site">
        <div class="grid gap-8 lg:grid-cols-12 lg:items-end">
          <div class="section-head lg:col-span-7" data-reveal>
            <p class="eyebrow">05 / Process</p>
            <h2 id="process-title" class="t-h2">From first sample to <span class="t-em">final dispatch.</span></h2>
          </div>
          <p class="t-lead lg:col-span-4 lg:col-start-9" data-reveal>
            A clear, step-by-step workflow keeps you informed from the first conversation to delivery.
          </p>
        </div>

        <div class="mt-16" data-reveal>
          <x-media :class="'aspect-[4/3] md:aspect-[21/9]'" :src="'images/placeholders/manufacturing/fabric-cutting.jpg'" :alt="'Fabric being cut on a cutting table'" :brief="'Fabric cutting on a cutting table · wide · 21:9'" />
        </div>

        <div class="mt-16 lg:mt-20">
          <x-process-steps />
        </div>
      </div>
    </section>

    {{-- ============================================================ 9. QUALITY --}}
    <section class="surface-ink-2 section-y" aria-labelledby="quality-title">
      <div class="container-site grid gap-14 lg:grid-cols-12 lg:items-center lg:gap-6">
        <div class="lg:col-span-6" data-reveal>
          <x-media :class="'aspect-[4/5]'" :src="'images/placeholders/quality/inspection.jpg'" :alt="'Garment being measured during quality inspection'" :brief="'Quality inspection · garment measurement · 4:5'" />
        </div>

        <div class="lg:col-span-5 lg:col-start-8">
          <div class="section-head" data-reveal>
            <p class="eyebrow">06 / Quality Assurance</p>
            <h2 id="quality-title" class="t-h2">Checked before it <span class="t-em">leaves us.</span></h2>
            <p class="t-lead">
              Garments are checked against the approved sample during and after production, so what you approve is what you receive.
            </p>
          </div>

          <ul class="check-list mt-12" data-reveal>
            <li><i data-lucide="check"></i>Stitching &amp; seams</li>
            <li><i data-lucide="check"></i>Measurements &amp; fit</li>
            <li><i data-lucide="check"></i>Fabric &amp; colour consistency</li>
            <li><i data-lucide="check"></i>Zips, trims &amp; fasteners</li>
            <li><i data-lucide="check"></i>Finishing &amp; pressing</li>
            <li><i data-lucide="check"></i>Packing &amp; labelling</li>
          </ul>

          <a href="{{ route('quality') }}" class="link-arrow mt-12" data-reveal>
            Our quality process
            <i data-lucide="arrow-right"></i>
          </a>
        </div>
      </div>
    </section>

    {{-- ============================================================ 10. WHY CHOOSE --}}
    <section class="surface-bone section-y" aria-labelledby="why-title">
      <div class="container-site">
        <div class="grid gap-8 lg:grid-cols-12 lg:items-end">
          <div class="section-head lg:col-span-6" data-reveal>
            <p class="eyebrow">07 / Why Hudaya</p>
            <h2 id="why-title" class="t-h2">Why brands choose <span class="t-em">Hudaya Enterprises.</span></h2>
          </div>
          <p class="t-lead lg:col-span-4 lg:col-start-9" data-reveal>
            A focused manufacturer that treats your product with the same care as its own.
          </p>
        </div>

        <ul class="feature-grid mt-16" data-reveal-group>
          <li class="feature" data-reveal>
            <span class="feature__num">i.</span>
            <h3 class="feature__title">Category specialists</h3>
            <p class="feature__text">We concentrate on lowers and jackets, building deep knowledge of their fabrics, patterns and construction.</p>
          </li>
          <li class="feature" data-reveal>
            <span class="feature__num">ii.</span>
            <h3 class="feature__title">Your label, your specs</h3>
            <p class="feature__text">Private label production made to your designs, fabrics, colours and branding.</p>
          </li>
          <li class="feature" data-reveal>
            <span class="feature__num">iii.</span>
            <h3 class="feature__title">Sample before bulk</h3>
            <p class="feature__text">Every order starts with a sample, so fit and finish are approved before production begins.</p>
          </li>
          <li class="feature" data-reveal>
            <span class="feature__num">iv.</span>
            <h3 class="feature__title">Quality at every step</h3>
            <p class="feature__text">Checks during and after production help keep every piece consistent with the approved sample.</p>
          </li>
          <li class="feature" data-reveal>
            <span class="feature__num">v.</span>
            <h3 class="feature__title">Clear communication</h3>
            <p class="feature__text">Direct contact and regular updates from inquiry to dispatch, including on WhatsApp.</p>
          </li>
          <li class="feature" data-reveal>
            <span class="feature__num">vi.</span>
            <h3 class="feature__title">Built for B2B</h3>
            <p class="feature__text">Set up for brands, wholesalers and retailers placing sample, bulk and repeat orders.</p>
          </li>
        </ul>
      </div>
    </section>

    {{-- ============================================================ 11. GALLERY PREVIEW --}}
    <section class="surface-ink section-y" aria-labelledby="gallery-title">
      <div class="container-site">
        <div class="flex flex-col gap-8 md:flex-row md:items-end md:justify-between">
          <div class="section-head" data-reveal>
            <p class="eyebrow">08 / Gallery</p>
            <h2 id="gallery-title" class="t-h2">Inside the <span class="t-em">work.</span></h2>
          </div>
          <a href="{{ route('gallery') }}" class="link-arrow" data-reveal>
            View full gallery
            <i data-lucide="arrow-right"></i>
          </a>
        </div>

        @if ($mosaic->isNotEmpty())
        <div class="mosaic mt-14" data-reveal-group>
          @foreach ($mosaic as $item)
          {{-- Slots a–e: the mosaic layout classes are fixed in sections.css. --}}
          <figure class="mosaic__item mosaic__item--{{ ['a', 'b', 'c', 'd', 'e'][$loop->index] }}" data-reveal>
            <x-media :src="$item->image" :alt="$item->image_alt" :brief="$item->caption" />
            <figcaption class="mosaic__caption">{{ $item->short_caption ?: $item->caption }}</figcaption>
          </figure>
          @endforeach
        </div>
        @endif
      </div>
    </section>

    {{-- ============================================================ 12. BUSINESS INQUIRY CTA --}}
    <x-cta-band />
  </main>

  {{-- ============================================================ 13. FOOTER --}}
</x-layouts.app>
