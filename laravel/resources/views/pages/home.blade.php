{{-- Converted from static-site/src/index.html (Module 1). Data becomes dynamic in later modules. --}}
<x-layouts.app :title="'Premium Lowers & Jackets Manufacturer'" :description="'Premium lowers, track pants and jackets manufactured for clothing brands, retailers, wholesalers and private label businesses. Request a quote.'" nav-active="home">
  <main id="main">

    {{-- ============================================================ 3. HERO --}}
    <section class="hero surface-ink" aria-labelledby="hero-title">
      <div class="hero__media" aria-hidden="true">
        <x-media :src="'images/placeholders/hero/hero-main.jpg'" :alt="''" :brief="'Hero · Model in black track jacket and track pants · studio light · dark backdrop · 16:9'" :img-class="'hero-settle'" :loading="'eager'" :priority="'high'" />
      </div>
      <div class="hero__overlay" aria-hidden="true"></div>

      <div class="container-site hero__inner">
        <div class="hero__content">
          <p class="eyebrow" data-reveal>Garment Manufacturing Excellence</p>
          <h1 id="hero-title" class="t-display mt-8" data-reveal>
            Crafted with <span class="t-em">precision.</span><br>
            Built for excellence.
          </h1>
          <div class="mt-10 grid gap-10 lg:grid-cols-12 lg:items-end">
            <p class="hero__lead lg:col-span-5" data-reveal>
              Premium lower and jacket manufacturing for brands, retailers and businesses.
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
          <a href="{{ route('products.category', 'lowers') }}" class="hero__strip-item">
            <span><span class="hero__strip-index">01</span>Lowers &amp; Track Pants</span>
            <i data-lucide="arrow-up-right"></i>
          </a>
          <a href="{{ route('products.category', 'jackets') }}" class="hero__strip-item">
            <span><span class="hero__strip-index">02</span>Jackets</span>
            <i data-lucide="arrow-up-right"></i>
          </a>
          <a href="{{ route('contact') }}#quote" class="hero__strip-item">
            <span><span class="hero__strip-index">03</span>Private Label &amp; Bulk</span>
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
          <div class="lg:col-span-7" data-reveal>
            <a href="{{ route('products.category', 'lowers') }}" class="category-tile">
              <x-media :class="'category-tile__media'" :src="'images/placeholders/lowers/category-lowers.jpg'" :alt="'Black track pants photographed in a studio'" :brief="'Black track pants · studio · dark backdrop · 4:5'" />
              <div class="media-overlay z-[1]" aria-hidden="true"></div>
              <div class="category-tile__content">
                <span class="category-tile__index">01 / Collection</span>
                <h3 class="category-tile__title">Lowers &amp; <span class="t-em">Track Pants</span></h3>
                <span class="link-arrow">View collection <i data-lucide="arrow-right"></i></span>
              </div>
            </a>
            <ul class="subtype-list mt-6">
              <li>Track pants</li><li>Joggers</li><li>Sports lowers</li><li>Casual lowers</li>
            </ul>
          </div>

          <div class="lg:col-span-5 lg:mt-40" data-reveal>
            <a href="{{ route('products.category', 'jackets') }}" class="category-tile">
              <x-media :class="'category-tile__media'" :src="'images/placeholders/jackets/category-jackets.jpg'" :alt="'Black track jacket on a dark background'" :brief="'Black track jacket · dark backdrop · 4:5'" />
              <div class="media-overlay z-[1]" aria-hidden="true"></div>
              <div class="category-tile__content">
                <span class="category-tile__index">02 / Collection</span>
                <h3 class="category-tile__title">Jackets</h3>
                <span class="link-arrow">View collection <i data-lucide="arrow-right"></i></span>
              </div>
            </a>
            <ul class="subtype-list mt-6">
              <li>Sports</li><li>Track</li><li>Winter</li><li>Fashion</li>
            </ul>
          </div>
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
            <button type="button" class="filter-tab" data-filter="lowers" aria-pressed="false">Lowers</button>
            <button type="button" class="filter-tab" data-filter="jackets" aria-pressed="false">Jackets</button>
          </div>
        </div>
        <p class="sr-only" data-filter-status aria-live="polite"></p>

        <div class="mt-14 grid grid-cols-2 gap-x-4 gap-y-12 md:gap-x-6 lg:grid-cols-4 lg:gap-y-16" data-reveal-group>
          <x-product-card :name="'Classic Track Pant'" :category="'Lowers'" :type="'Track Pants'" :filter="'lowers'" :src="'images/placeholders/lowers/classic-track-pant.jpg'" :alt="'Black track pant with side panel'" :brief="'Black track pant · full length · studio · 4:5'" />
          <x-product-card :name="'Tapered Jogger'" :category="'Lowers'" :type="'Joggers'" :filter="'lowers'" :src="'images/placeholders/lowers/tapered-jogger.jpg'" :alt="'Tapered jogger with ribbed cuffs'" :brief="'Tapered jogger · ribbed cuffs · studio · 4:5'" />
          <x-product-card :name="'Zip Track Jacket'" :category="'Jackets'" :type="'Track Jacket'" :filter="'jackets'" :src="'images/placeholders/jackets/zip-track-jacket.jpg'" :alt="'Full-zip track jacket'" :brief="'Full-zip track jacket · studio · 4:5'" />
          <x-product-card :name="'Quilted Winter Jacket'" :category="'Jackets'" :type="'Winter'" :filter="'jackets'" :src="'images/placeholders/jackets/quilted-winter-jacket.jpg'" :alt="'Quilted winter jacket'" :brief="'Quilted winter jacket · studio · 4:5'" />
          <x-product-card :name="'Performance Sports Lower'" :category="'Lowers'" :type="'Sports'" :filter="'lowers'" :src="'images/placeholders/lowers/sports-lower.jpg'" :alt="'Lightweight sports lower'" :brief="'Sports lower · lightweight fabric · studio · 4:5'" />
          <x-product-card :name="'Sports Windcheater'" :category="'Jackets'" :type="'Sports'" :filter="'jackets'" :src="'images/placeholders/jackets/windcheater.jpg'" :alt="'Lightweight sports windcheater jacket'" :brief="'Sports windcheater · studio · 4:5'" />
          <x-product-card :name="'Casual Lounge Lower'" :category="'Lowers'" :type="'Casual'" :filter="'lowers'" :src="'images/placeholders/lowers/casual-lower.jpg'" :alt="'Relaxed casual lower'" :brief="'Casual lower · relaxed fit · studio · 4:5'" />
          <x-product-card :name="'Bomber Jacket'" :category="'Jackets'" :type="'Fashion'" :filter="'jackets'" :src="'images/placeholders/jackets/bomber-jacket.jpg'" :alt="'Black bomber jacket'" :brief="'Black bomber jacket · studio · 4:5'" />
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

        <div class="mosaic mt-14" data-reveal-group>
          <figure class="mosaic__item mosaic__item--a" data-reveal>
            <x-media :src="'images/placeholders/manufacturing/stitching-line.jpg'" :alt="'Garments being stitched on industrial sewing machines'" :brief="'Stitching on industrial machines · wide'" />
            <figcaption class="mosaic__caption">Stitching</figcaption>
          </figure>
          <figure class="mosaic__item mosaic__item--b" data-reveal>
            <x-media :src="'images/placeholders/details/zip-detail.jpg'" :alt="'Close-up of a jacket zip and pull'" :brief="'Jacket zip detail · close-up'" />
            <figcaption class="mosaic__caption">Zip &amp; trim detail</figcaption>
          </figure>
          <figure class="mosaic__item mosaic__item--c" data-reveal>
            <x-media :src="'images/placeholders/details/waistband-detail.jpg'" :alt="'Close-up of an elasticated waistband with drawcord'" :brief="'Waistband & drawcord · close-up'" />
            <figcaption class="mosaic__caption">Waistband detail</figcaption>
          </figure>
          <figure class="mosaic__item mosaic__item--d" data-reveal>
            <x-media :src="'images/placeholders/manufacturing/finishing.jpg'" :alt="'Garment being pressed during finishing'" :brief="'Pressing & finishing'" />
            <figcaption class="mosaic__caption">Finishing</figcaption>
          </figure>
          <figure class="mosaic__item mosaic__item--e" data-reveal>
            <x-media :src="'images/placeholders/jackets/jacket-detail.jpg'" :alt="'Folded black jackets ready for packing'" :brief="'Folded jackets · ready for packing · wide'" />
            <figcaption class="mosaic__caption">Ready for dispatch</figcaption>
          </figure>
        </div>
      </div>
    </section>

    {{-- ============================================================ 12. BUSINESS INQUIRY CTA --}}
    <x-cta-band />
  </main>

  {{-- ============================================================ 13. FOOTER --}}
</x-layouts.app>
