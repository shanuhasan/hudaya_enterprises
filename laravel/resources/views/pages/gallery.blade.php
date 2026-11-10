{{-- Converted from static-site/src/gallery.html (Module 1). Data becomes dynamic in later modules. --}}
<x-layouts.app :title="'Gallery'" :description="'A visual look at lowers, jackets, garment details and production at Hudaya Enterprises.'" nav-active="gallery">
  <main id="main">
    <x-page-hero :crumb="'Gallery'" :eyebrow="'Gallery'" :title="'Inside the'" :title-em="'work.'" :lead="'Products, details and production — the garment types, finishes and processes we work with.'" :hero-size="'compact'" :src="'images/placeholders/hero/gallery.jpg'" :alt="''" :brief="'Gallery hero · rows of black garments on a rail · dark, editorial · 16:9'" />

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
            <button type="button" class="filter-tab" data-filter="lowers" aria-pressed="false">Lowers</button>
            <button type="button" class="filter-tab" data-filter="jackets" aria-pressed="false">Jackets</button>
            <button type="button" class="filter-tab" data-filter="manufacturing" aria-pressed="false">Manufacturing</button>
            <button type="button" class="filter-tab" data-filter="details" aria-pressed="false">Details</button>
          </div>
        </div>
        <p class="sr-only" data-filter-status aria-live="polite"></p>

        <div class="gallery-grid mt-14">
          <x-gallery-item :caption="'Classic track pant'" :group="'lowers'" :group-label="'Lowers'" :ratio="'aspect-[4/5]'" :src="'images/placeholders/lowers/classic-track-pant.jpg'" :alt="'Black track pant photographed in a studio'" :brief="'Black track pant · studio'" />
          <x-gallery-item :caption="'Stitching on industrial machines'" :group="'manufacturing'" :group-label="'Manufacturing'" :ratio="'aspect-[4/3]'" :src="'images/placeholders/manufacturing/stitching-line.jpg'" :alt="'Garments being stitched on industrial sewing machines'" :brief="'Stitching on industrial machines'" />
          <x-gallery-item :caption="'Full-zip track jacket'" :group="'jackets'" :group-label="'Jackets'" :ratio="'aspect-[3/4]'" :src="'images/placeholders/jackets/zip-track-jacket.jpg'" :alt="'Full-zip track jacket'" :brief="'Full-zip track jacket · studio'" />
          <x-gallery-item :caption="'Waistband and drawcord'" :group="'details'" :group-label="'Details'" :ratio="'aspect-square'" :src="'images/placeholders/details/waistband-detail.jpg'" :alt="'Close-up of an elasticated waistband with drawcord'" :brief="'Waistband & drawcord · close-up'" />
          <x-gallery-item :caption="'Fabric cutting'" :group="'manufacturing'" :group-label="'Manufacturing'" :ratio="'aspect-[4/5]'" :src="'images/placeholders/manufacturing/fabric-cutting.jpg'" :alt="'Fabric being cut on a cutting table'" :brief="'Fabric cutting on a cutting table'" />
          <x-gallery-item :caption="'Tapered jogger'" :group="'lowers'" :group-label="'Lowers'" :ratio="'aspect-[3/4]'" :src="'images/placeholders/lowers/tapered-jogger.jpg'" :alt="'Tapered jogger with ribbed cuffs'" :brief="'Tapered jogger · studio'" />
          <x-gallery-item :caption="'Jacket zip detail'" :group="'details'" :group-label="'Details'" :ratio="'aspect-[4/3]'" :src="'images/placeholders/details/zip-detail.jpg'" :alt="'Close-up of a jacket zip and pull'" :brief="'Jacket zip detail · close-up'" />
          <x-gallery-item :caption="'Quilted winter jacket'" :group="'jackets'" :group-label="'Jackets'" :ratio="'aspect-[4/5]'" :src="'images/placeholders/jackets/quilted-winter-jacket.jpg'" :alt="'Quilted winter jacket'" :brief="'Quilted winter jacket · studio'" />
          <x-gallery-item :caption="'Pattern making'" :group="'manufacturing'" :group-label="'Manufacturing'" :ratio="'aspect-square'" :src="'images/placeholders/manufacturing/pattern-making.jpg'" :alt="'Garment pattern pieces laid out on a table'" :brief="'Pattern pieces on a table'" />
          <x-gallery-item :caption="'Stitching close-up'" :group="'details'" :group-label="'Details'" :ratio="'aspect-[3/4]'" :src="'images/placeholders/details/stitching-closeup.jpg'" :alt="'Close-up of stitching along a black garment seam'" :brief="'Stitching close-up on a seam'" />
          <x-gallery-item :caption="'Sports lower'" :group="'lowers'" :group-label="'Lowers'" :ratio="'aspect-[4/3]'" :src="'images/placeholders/lowers/sports-lower.jpg'" :alt="'Lightweight sports lower'" :brief="'Sports lower · studio'" />
          <x-gallery-item :caption="'Pressing and finishing'" :group="'manufacturing'" :group-label="'Manufacturing'" :ratio="'aspect-[4/5]'" :src="'images/placeholders/manufacturing/finishing.jpg'" :alt="'Garment being pressed during finishing'" :brief="'Pressing & finishing'" />
          <x-gallery-item :caption="'Bomber jacket'" :group="'jackets'" :group-label="'Jackets'" :ratio="'aspect-square'" :src="'images/placeholders/jackets/bomber-jacket.jpg'" :alt="'Black bomber jacket'" :brief="'Black bomber jacket · studio'" />
          <x-gallery-item :caption="'Fabric texture'" :group="'details'" :group-label="'Details'" :ratio="'aspect-[4/5]'" :src="'images/placeholders/details/fabric-texture.jpg'" :alt="'Folded black fabric showing its texture'" :brief="'Folded fabric texture'" />
          <x-gallery-item :caption="'Quality inspection'" :group="'manufacturing'" :group-label="'Manufacturing'" :ratio="'aspect-[3/4]'" :src="'images/placeholders/quality/inspection.jpg'" :alt="'Garment being measured during quality inspection'" :brief="'Quality inspection · measurement'" />
          <x-gallery-item :caption="'Sports windcheater'" :group="'jackets'" :group-label="'Jackets'" :ratio="'aspect-[4/3]'" :src="'images/placeholders/jackets/windcheater.jpg'" :alt="'Lightweight sports windcheater'" :brief="'Sports windcheater · studio'" />
          <x-gallery-item :caption="'Ready for packing'" :group="'manufacturing'" :group-label="'Manufacturing'" :ratio="'aspect-[4/5]'" :src="'images/placeholders/jackets/jacket-detail.jpg'" :alt="'Folded jackets ready for packing'" :brief="'Folded garments ready for packing'" />
          <x-gallery-item :caption="'Casual lower'" :group="'lowers'" :group-label="'Lowers'" :ratio="'aspect-[4/5]'" :src="'images/placeholders/lowers/casual-lower.jpg'" :alt="'Relaxed casual lower'" :brief="'Casual lower · studio'" />
        </div>
      </div>
    </section>

    <x-cta-band />
  </main>

  <x-lightbox />
</x-layouts.app>
