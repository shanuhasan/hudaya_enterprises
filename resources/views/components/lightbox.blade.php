{{-- Component: lightbox — native <dialog>; behaviour in resources/js/modules/gallery.js --}}
<dialog class="lightbox" data-lightbox aria-label="Image viewer">
  <div class="lightbox__top">
    <p class="lightbox__counter" data-lightbox-counter aria-live="polite"></p>
    <button type="button" class="btn-icon" data-lightbox-close>
      <i data-lucide="x"></i><span class="sr-only">Close image viewer</span>
    </button>
  </div>
  <div class="lightbox__stage" data-lightbox-stage></div>
  <div class="lightbox__bottom">
    <p class="lightbox__caption" data-lightbox-caption></p>
    <div class="lightbox__nav">
      <button type="button" class="btn-icon" data-lightbox-prev>
        <i data-lucide="chevron-left"></i><span class="sr-only">Previous image</span>
      </button>
      <button type="button" class="btn-icon" data-lightbox-next>
        <i data-lucide="chevron-right"></i><span class="sr-only">Next image</span>
      </button>
    </div>
  </div>
</dialog>
