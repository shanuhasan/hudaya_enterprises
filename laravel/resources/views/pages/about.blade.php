<x-layouts.app :title="$page->meta_title" :description="$page->meta_description" nav-active="about">
  <main id="main">
    <x-page-hero :crumb="'About'" :eyebrow="$page->hero_eyebrow" :title="$page->hero_title" :title-em="$page->hero_title_em" :lead="$page->hero_lead" :src="$page->hero_image" :alt="$page->hero_image_alt" :brief="'About hero · tailor hands working on a black garment · dark, editorial light · 16:9'" />

    {{-- ============================================================ OUR STORY --}}
    <section class="surface-bone section-y" aria-labelledby="story-title">
      <div class="container-site grid gap-16 lg:grid-cols-12 lg:gap-6">
        <div class="lg:col-span-6">
          <p class="eyebrow" data-reveal>01 / Our story</p>
          <h2 id="story-title" class="t-h2 mt-8" data-reveal>Two categories, made with <span class="t-em">specialist care.</span></h2>
          <div class="mt-12 grid gap-6" data-reveal>
            <p class="t-body">
              {{ $site->name }} manufactures lowers, track pants and jackets for clothing brands, wholesalers,
              retailers, fashion businesses and private label brands.
            </p>
            <p class="t-body">
              By focusing on two categories, we can give every pattern, fabric and finishing detail the attention it needs —
              from the first sample to repeat bulk orders.
            </p>
            {{-- Client to supply: founding year, location, founders, milestones. Do not invent. --}}
            <p class="placeholder-note">
              [Company story — when and where Hudaya Enterprises was founded, the people behind it, and how the business has grown.
              To be provided by Hudaya Enterprises.]
            </p>
          </div>
        </div>
        <div class="lg:col-span-5 lg:col-start-8" data-reveal>
          <div class="image-pair">
            <x-media :class="'image-pair__main'" :src="'images/placeholders/manufacturing/pattern-making.jpg'" :alt="'Garment pattern pieces laid out on a table'" :brief="'Pattern pieces on a cutting table · 4:5'" />
            <x-media :class="'image-pair__inset'" :src="'images/placeholders/details/fabric-texture.jpg'" :alt="'Folded black fabric showing its texture'" :brief="'Folded fabric texture · 1:1'" />
          </div>
        </div>
      </div>
    </section>

    {{-- ============================================================ MISSION & VISION --}}
    {{-- Mission / vision wording is a draft for the client to confirm. --}}
    <section class="surface-ink section-y" aria-labelledby="mission-title">
      <div class="container-site">
        <h2 id="mission-title" class="sr-only">Mission and vision</h2>
        <div class="grid gap-16 lg:grid-cols-12 lg:gap-6">
          <div class="lg:col-span-5" data-reveal>
            <p class="eyebrow">02 / Mission</p>
            <p class="t-quote mt-8">To manufacture lowers and jackets that our clients are proud to put their name on.</p>
          </div>
          <div class="lg:col-span-5 lg:col-start-8 lg:mt-32" data-reveal>
            <p class="eyebrow">03 / Vision</p>
            <p class="t-quote mt-8">To be a trusted, long-term manufacturing partner for brands that value quality and consistency.</p>
          </div>
        </div>
      </div>
    </section>

    {{-- ============================================================ VALUES --}}
    <section class="surface-bone section-y" aria-labelledby="values-title">
      <div class="container-site grid gap-16 lg:grid-cols-12 lg:gap-6">
        <div class="lg:col-span-5">
          <div class="section-head lg:sticky lg:top-32" data-reveal>
            <p class="eyebrow">04 / Values</p>
            <h2 id="values-title" class="t-h2">What guides <span class="t-em">our work.</span></h2>
            <p class="t-lead">Four principles shape how we manufacture and how we work with every client.</p>
          </div>
        </div>
        <div class="lg:col-span-6 lg:col-start-7">
          <ol class="index-list" data-reveal-group>
            <li class="index-list__item" data-reveal>
              <span class="index-list__num">01</span>
              <h3 class="index-list__title">Precision</h3>
              <p class="index-list__text">Accurate patterns, careful cutting and consistent stitching, piece after piece.</p>
            </li>
            <li class="index-list__item" data-reveal>
              <span class="index-list__num">02</span>
              <h3 class="index-list__title">Integrity</h3>
              <p class="index-list__text">Honest timelines, clear pricing and open communication throughout every order.</p>
            </li>
            <li class="index-list__item" data-reveal>
              <span class="index-list__num">03</span>
              <h3 class="index-list__title">Craftsmanship</h3>
              <p class="index-list__text">Respect for fabric, construction and the finishing details that customers notice.</p>
            </li>
            <li class="index-list__item" data-reveal>
              <span class="index-list__num">04</span>
              <h3 class="index-list__title">Partnership</h3>
              <p class="index-list__text">We treat your product as if it carried our own name, and plan for repeat orders together.</p>
            </li>
          </ol>
        </div>
      </div>
    </section>

    {{-- ============================================================ HOW WE WORK --}}
    <section class="surface-ink-2 section-y" aria-labelledby="approach-title">
      <div class="container-site">
        <div class="grid gap-14 lg:grid-cols-12 lg:items-center lg:gap-6">
          <div class="lg:col-span-7" data-reveal>
            <x-media :class="'aspect-[4/3]'" :src="'images/placeholders/manufacturing/stitching-line.jpg'" :alt="'Garments being stitched on industrial sewing machines'" :brief="'Stitching on industrial machines · 4:3'" />
          </div>
          <div class="lg:col-span-4 lg:col-start-9">
            <div class="section-head" data-reveal>
              <p class="eyebrow">05 / How we work</p>
              <h2 id="approach-title" class="t-h2">Sample first. <span class="t-em">Then scale.</span></h2>
              <p class="t-lead">Every order starts with an approved sample. Bulk production follows that sample — so there are no surprises when your order arrives.</p>
            </div>
            <a href="{{ route('manufacturing') }}" class="link-arrow mt-10" data-reveal>See the full workflow <i data-lucide="arrow-right"></i></a>
          </div>
        </div>
        <div class="mt-20 lg:mt-28">
          <x-process-steps />
        </div>
      </div>
    </section>

    {{-- ============================================================ WHO WE SERVE --}}
    <section class="surface-ink section-y" aria-labelledby="serve-title">
      <div class="container-site grid gap-10 lg:grid-cols-12 lg:gap-6">
        <div class="lg:col-span-4" data-reveal>
          <p class="eyebrow">06 / Who we work with</p>
          <h2 id="serve-title" class="t-h2 mt-8">Built for <span class="t-em">B2B.</span></h2>
        </div>
        <div class="lg:col-span-7 lg:col-start-6" data-reveal>
          <ul class="audience-list">
            <li>Clothing brands</li>
            <li>Wholesalers</li>
            <li>Retailers</li>
            <li>Fashion businesses</li>
            <li><span class="t-em">Private label</span> brands</li>
            <li>Bulk buyers</li>
          </ul>
          <p class="t-body mt-10">Whether you are launching a first collection or restocking a best-seller, we can discuss sample, bulk and repeat orders.</p>
        </div>
      </div>
    </section>

    <x-cta-band :eyebrow="'Work with us'" :title="'Let us build'" :title-em="'your next collection.'" />
  </main>
</x-layouts.app>
