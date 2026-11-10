<x-layouts.app :title="$page->meta_title" :description="$page->meta_description" nav-active="quality">
  <main id="main">
    <x-page-hero :crumb="'Quality'" :eyebrow="$page->hero_eyebrow" :title="$page->hero_title" :title-em="$page->hero_title_em" :lead="$page->hero_lead" :src="$page->hero_image" :alt="$page->hero_image_alt" :brief="'Quality hero · measuring tape on a black garment · close-up · 16:9'" />

    {{-- ============================================================ PHILOSOPHY --}}
    <section class="surface-bone section-y" aria-labelledby="philosophy-title">
      <div class="container-site grid gap-14 lg:grid-cols-12 lg:gap-6">
        <div class="lg:col-span-7" data-reveal>
          <p class="eyebrow">01 / Our approach</p>
          <h2 id="philosophy-title" class="sr-only">Our approach to quality</h2>
          <p class="t-quote mt-8">“Quality is built in during production — not inspected in at the end.”</p>
        </div>
        <div class="grid gap-6 lg:col-span-4 lg:col-start-9 lg:self-end" data-reveal>
          <p class="t-body">Every order is measured against one reference: the sample you approved. Checks happen from the moment fabric arrives to the moment garments are packed.</p>
          <p class="t-body">Issues found early are corrected early, which keeps bulk production consistent and on schedule.</p>
        </div>
      </div>
    </section>

    {{-- ============================================================ CHECKPOINTS --}}
    <section class="surface-ink section-y" aria-labelledby="checkpoints-title">
      <div class="container-site grid gap-16 lg:grid-cols-12 lg:gap-6">
        <div class="lg:col-span-5">
          <div class="lg:sticky lg:top-32">
            <div class="section-head" data-reveal>
              <p class="eyebrow">02 / Inspection checkpoints</p>
              <h2 id="checkpoints-title" class="t-h2">Six points of <span class="t-em">inspection.</span></h2>
              <p class="t-lead">Garments are checked at each key stage of production, not only at the end.</p>
            </div>
            <div class="mt-12 hidden lg:block" data-reveal>
              <x-media :class="'aspect-[4/3]'" :src="'images/placeholders/quality/inspection.jpg'" :alt="'Garment being measured during quality inspection'" :brief="'Quality inspection · garment measurement · 4:3'" />
            </div>
          </div>
        </div>

        <div class="lg:col-span-6 lg:col-start-7">
          <ol class="index-list" data-reveal-group>
            <li class="index-list__item" data-reveal>
              <span class="index-list__num">01</span>
              <h3 class="index-list__title">Incoming fabric</h3>
              <p class="index-list__text">Fabric is checked for shade, defects and consistency before cutting begins.</p>
            </li>
            <li class="index-list__item" data-reveal>
              <span class="index-list__num">02</span>
              <h3 class="index-list__title">Cutting accuracy</h3>
              <p class="index-list__text">Cut panels are checked against the approved patterns before they go to stitching.</p>
            </li>
            <li class="index-list__item" data-reveal>
              <span class="index-list__num">03</span>
              <h3 class="index-list__title">Inline inspection</h3>
              <p class="index-list__text">Stitching quality is checked during production so issues are caught and corrected early.</p>
            </li>
            <li class="index-list__item" data-reveal>
              <span class="index-list__num">04</span>
              <h3 class="index-list__title">Measurement checks</h3>
              <p class="index-list__text">Garments are measured against your size chart and the approved sample.</p>
            </li>
            <li class="index-list__item" data-reveal>
              <span class="index-list__num">05</span>
              <h3 class="index-list__title">Finishing inspection</h3>
              <p class="index-list__text">Threads, trims, labels and pressing are checked for a clean, consistent finish.</p>
            </li>
            <li class="index-list__item" data-reveal>
              <span class="index-list__num">06</span>
              <h3 class="index-list__title">Final inspection</h3>
              <p class="index-list__text">A final check before packing confirms each garment is ready for dispatch.</p>
            </li>
          </ol>
        </div>
      </div>
    </section>

    {{-- ============================================================ WHAT WE CHECK --}}
    <section class="surface-bone section-y" aria-labelledby="check-title">
      <div class="container-site grid gap-14 lg:grid-cols-12 lg:gap-6">
        <div class="lg:col-span-4">
          <div class="section-head" data-reveal>
            <p class="eyebrow">03 / What we check</p>
            <h2 id="check-title" class="t-h2">The details that <span class="t-em">matter.</span></h2>
          </div>
        </div>
        <div class="lg:col-span-7 lg:col-start-6" data-reveal>
          <ul class="check-list">
            <li><i data-lucide="check"></i>Stitch density &amp; tension</li>
            <li><i data-lucide="check"></i>Seam strength &amp; alignment</li>
            <li><i data-lucide="check"></i>Measurements to size chart</li>
            <li><i data-lucide="check"></i>Fit against approved sample</li>
            <li><i data-lucide="check"></i>Fabric defects &amp; shading</li>
            <li><i data-lucide="check"></i>Colour consistency</li>
            <li><i data-lucide="check"></i>Zips, drawcords &amp; fasteners</li>
            <li><i data-lucide="check"></i>Labels &amp; tags</li>
            <li><i data-lucide="check"></i>Loose threads &amp; trimming</li>
            <li><i data-lucide="check"></i>Pressing &amp; overall finish</li>
            <li><i data-lucide="check"></i>Folding &amp; packing</li>
            <li><i data-lucide="check"></i>Quantity &amp; size ratios</li>
          </ul>
        </div>
      </div>
    </section>

    {{-- ============================================================ FINISHING --}}
    <section class="surface-ink-2 section-y" aria-labelledby="finishing-title">
      <div class="container-site">
        <div class="grid gap-8 lg:grid-cols-12 lg:items-end">
          <div class="section-head lg:col-span-6" data-reveal>
            <p class="eyebrow">04 / Finishing</p>
            <h2 id="finishing-title" class="t-h2">Finished <span class="t-em">with care.</span></h2>
          </div>
          <p class="t-lead lg:col-span-4 lg:col-start-9" data-reveal>The final steps that turn a well-made garment into one that is ready to sell.</p>
        </div>

        <div class="mt-16 grid grid-cols-1 gap-x-6 gap-y-14 sm:grid-cols-2 lg:grid-cols-4" data-reveal-group>
          <x-type-card :index="'01'" :title="'Thread trimming'" :text="'Loose threads are removed from every seam and edge.'" :src="'images/placeholders/quality/thread-trimming.jpg'" :alt="'Loose threads being trimmed from a garment'" :brief="'Thread trimming · close-up · 4:5'" />
          <x-type-card :index="'02'" :title="'Pressing'" :text="'Garments are pressed for a crisp, clean appearance.'" :src="'images/placeholders/manufacturing/finishing.jpg'" :alt="'Garment being pressed'" :brief="'Pressing · steam iron · 4:5'" />
          <x-type-card :index="'03'" :title="'Labelling & tagging'" :text="'Brand labels, care labels and tags are checked and attached.'" :src="'images/placeholders/quality/labelling.jpg'" :alt="'Brand label and tag on a garment'" :brief="'Labels & hang tags · close-up · 4:5'" />
          <x-type-card :index="'04'" :title="'Folding & packing'" :text="'Garments are folded and packed to your requirements.'" :src="'images/placeholders/quality/packing.jpg'" :alt="'Garments being folded and packed'" :brief="'Folding & packing · 4:5'" />
        </div>
      </div>
    </section>

    {{-- ============================================================ CERTIFICATIONS (only when supplied) --}}
    @if ($site->certifications)
    <section class="surface-bone section-y-compact" aria-labelledby="cert-title">
      <div class="container-site grid gap-8 lg:grid-cols-12 lg:gap-6">
        <div class="lg:col-span-4">
          <p class="eyebrow">05 / Certifications</p>
          <h2 id="cert-title" class="t-h3 mt-6">Certifications</h2>
        </div>
        <p class="t-lead lg:col-span-7 lg:col-start-6">{{ $site->certifications }}</p>
      </div>
    </section>
    @endif

    <x-cta-band :eyebrow="'Quality you can rely on'" :title="'Start with'" :title-em="'a sample.'" :text="'See the quality for yourself. Share your requirements and we will discuss sampling for your style.'" />
  </main>
</x-layouts.app>
