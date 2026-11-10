<x-layouts.app :title="$page->meta_title" :description="$page->meta_description" nav-active="gallery">
  <main id="main">
    <x-page-hero :crumb="'Gallery'" :eyebrow="$page->hero_eyebrow" :title="$page->hero_title" :title-em="$page->hero_title_em" :lead="$page->hero_lead" :hero-size="'compact'" :src="$page->hero_image" :alt="$page->hero_image_alt" :brief="'Gallery hero · rows of black garments on a rail · dark, editorial · 16:9'" />

    {{-- ============================================================ GALLERY --}}
    <section class="surface-ink section-y" aria-labelledby="gallery-title" data-collection-filter>
      <div class="container-site">
        <div class="flex flex-col gap-10 lg:flex-row lg:items-end lg:justify-between">
          <div class="section-head" data-reveal>
            <p class="eyebrow">01 / Collection</p>
            <h2 id="gallery-title" class="t-h2">Browse the <span class="t-em">gallery.</span></h2>
            @if ($site->photo_notice)<p class="t-small mt-5 max-w-md">{{ $site->photo_notice }}</p>@endif
          </div>
          <div class="filter-tabs" role="group" aria-label="Filter gallery" data-reveal>
            <button type="button" class="filter-tab" data-filter="all" aria-pressed="true">All</button>
            @foreach ($groups as $group)
            <button type="button" class="filter-tab" data-filter="{{ $group->value }}" aria-pressed="false">{{ $group->label() }}</button>
            @endforeach
          </div>
        </div>
        <p class="sr-only" data-filter-status aria-live="polite"></p>

        <div class="gallery-grid mt-14">
          @foreach ($items as $item)
          <x-gallery-item :item="$item" />
          @endforeach
        </div>
      </div>
    </section>

    <x-cta-band />
  </main>

  <x-lightbox />
</x-layouts.app>
