{{--
  Component: gallery-item — filterable image that opens in the lightbox.
  Props: item — App\Models\GalleryItem
  The aspect classes are written out in full here on purpose: Tailwind only generates
  classes it can find literally in resources/ (never build them from database values).
--}}
@props(['item'])
@php
    $ratio = match ($item->aspect) {
        '3/4' => 'aspect-[3/4]',
        '4/3' => 'aspect-[4/3]',
        '1/1' => 'aspect-square',
        default => 'aspect-[4/5]',
    };
@endphp
<figure class="gallery-item" data-filter-item data-category="{{ $item->group->value }}" data-gallery-item data-caption="{{ $item->caption }}" data-brief="{{ $item->caption }}" data-reveal>
  <button type="button" class="gallery-item__btn">
    <span class="sr-only">Open image: {{ $item->caption }}</span>
    <x-media :src="$item->image" :alt="$item->image_alt" :brief="$item->caption" :class="$ratio" />
  </button>
  <figcaption class="gallery-item__caption"><span>{{ $item->caption }}</span><span>{{ $item->group->label() }}</span></figcaption>
</figure>
