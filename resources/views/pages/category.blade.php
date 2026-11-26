{{--
  Category page — /products/{category:slug}. One template for every category (Lowers, Jackets…);
  all text, images, types and products come from Admin → Categories / Products.
--}}
@php($slug = $category->slug)
<x-layouts.app :title="$category->meta_title" :description="$category->meta_description" nav-active="products">
  <main id="main">
    <x-page-hero :crumb="$category->name" crumb-parent="Products" :crumb-parent-href="route('products.index')"
                 :eyebrow="$category->hero_eyebrow" :title="$category->hero_title" :title-em="$category->hero_title_em"
                 :lead="$category->hero_lead" :src="$category->hero_image" alt="" :brief="$category->name.' hero image'" />

    {{-- ============================================================ INTRO --}}
    <section class="surface-bone section-y" aria-labelledby="{{ $slug }}-intro-title">
      <div class="container-site grid gap-14 lg:grid-cols-12 lg:items-center lg:gap-6">
        @if ($category->intro_image_position === 'left')
        <div class="lg:col-span-5" data-reveal>
          <x-media class="aspect-[4/5]" :src="$category->intro_image" :alt="$category->intro_image_alt" :brief="$category->name" />
        </div>
        @endif
        <div class="{{ $category->intro_image_position === 'left' ? 'lg:col-span-6 lg:col-start-7' : 'lg:col-span-6' }}" data-reveal>
          <p class="eyebrow">01 / The collection</p>
          <h2 id="{{ $slug }}-intro-title" class="t-h2 mt-8">{{ $category->intro_title }}@if ($category->intro_title_em) <span class="t-em">{{ $category->intro_title_em }}</span>@endif</h2>
          @if ($category->intro_text)<p class="t-lead mt-8">{{ $category->intro_text }}</p>@endif
          <a href="#styles" class="link-arrow mt-10">Browse styles <i data-lucide="arrow-down"></i></a>
        </div>
        @if ($category->intro_image_position !== 'left')
        <div class="lg:col-span-5 lg:col-start-8" data-reveal>
          <x-media class="aspect-[4/5]" :src="$category->intro_image" :alt="$category->intro_image_alt" :brief="$category->name" />
        </div>
        @endif
      </div>
    </section>

    @if ($category->types->isNotEmpty())
    {{-- ============================================================ TYPES --}}
    <section class="surface-ink section-y" aria-labelledby="{{ $slug }}-types-title">
      <div class="container-site">
        <div class="grid gap-8 lg:grid-cols-12 lg:items-end">
          <div class="section-head lg:col-span-6" data-reveal>
            <p class="eyebrow">02 / Range</p>
            <h2 id="{{ $slug }}-types-title" class="t-h2">{{ $category->types_title }}@if ($category->types_title_em) <span class="t-em">{{ $category->types_title_em }}</span>@endif</h2>
          </div>
          <p class="t-lead lg:col-span-4 lg:col-start-9" data-reveal>Choose a type to see example styles, or tell us about your own design.</p>
        </div>

        <div class="mt-16 grid grid-cols-1 gap-x-6 gap-y-16 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group>
          @foreach ($category->types as $type)
          <x-type-card :index="sprintf('%02d', $loop->iteration)" :title="$type->name" :text="$type->description" :filter-key="$type->slug"
                       :src="$type->image" :alt="$type->image_alt ?: $type->name" :brief="$type->name" />
          @endforeach
        </div>
      </div>
    </section>
    @endif

    {{-- ============================================================ STYLES (filterable) --}}
    <section id="styles" class="surface-ink-2 section-y" aria-labelledby="{{ $slug }}-styles-title" data-collection-filter>
      <div class="container-site">
        <div class="flex flex-col gap-10">
          <div class="section-head" data-reveal>
            <p class="eyebrow">{{ $category->types->isNotEmpty() ? '03' : '02' }} / Example styles</p>
            <h2 id="{{ $slug }}-styles-title" class="t-h2">Example <span class="t-em">styles.</span></h2>
            <p class="t-lead">Reference styles to start the conversation. Each can be adapted to your fabric, colour, fit and branding.</p>
          </div>
          <div class="collection-tools" data-reveal>
            <div class="filter-tabs" role="group" aria-label="Filter {{ Str::lower($category->name) }} by type">
              <button type="button" class="filter-tab" data-filter="all" aria-pressed="true">All</button>
              @foreach ($category->types as $type)
              <button type="button" class="filter-tab" data-filter="{{ $type->slug }}" aria-pressed="false">{{ $type->short_name }}</button>
              @endforeach
            </div>
            <x-collection-search :search-id="$slug" :search-label="'Search '.Str::lower($category->name)" />
          </div>
        </div>
        <p class="sr-only" data-filter-status aria-live="polite"></p>
        <x-collection-empty />

        @if ($category->products->isEmpty())
          <p class="t-lead mt-14">New styles are being added. Tell us what you are looking for and we will develop it to your specification.</p>
        @else
        <div class="mt-14 grid grid-cols-2 gap-x-4 gap-y-12 md:gap-x-6 lg:grid-cols-4 lg:gap-y-16" data-reveal-group>
          @foreach ($category->products as $product)
          <x-product-card :product="$product" />
          @endforeach
        </div>
        @endif
      </div>
    </section>

    @if ($category->specs)
    {{-- ============================================================ DETAILS --}}
    <section class="surface-bone section-y" aria-labelledby="{{ $slug }}-details-title">
      <div class="container-site grid gap-14 lg:grid-cols-12 lg:gap-6">
        <div class="lg:col-span-5">
          <div class="section-head" data-reveal>
            <p class="eyebrow">{{ $category->types->isNotEmpty() ? '04' : '03' }} / Details</p>
            <h2 id="{{ $slug }}-details-title" class="t-h2">Details you can <span class="t-em">specify.</span></h2>
            <p class="t-lead">Every element of the garment can be discussed. Availability of each option is confirmed during sampling.</p>
          </div>
        </div>
        <div class="lg:col-span-6 lg:col-start-7" data-reveal>
          <dl class="spec-list">
            @foreach ($category->specs as $spec)
            <div class="spec-list__row"><dt>{{ $spec['label'] }}</dt><dd>{{ $spec['value'] }}</dd></div>
            @endforeach
          </dl>
        </div>
      </div>
    </section>
    @endif

    <x-cta-band :eyebrow="$category->cta_eyebrow ?: 'Start a project'" :title="$category->cta_title ?: 'Request a quote'"
                :title-em="$category->cta_title_em ?: 'for '.Str::lower($category->name).'.'"
                :text="$category->cta_text ?: 'Share your style, fabric preferences and estimated quantities, and we will respond with sampling options and a quote.'" />
  </main>
</x-layouts.app>
