{{--
  Component: gallery-item — filterable image that opens in the lightbox.
  Props:
    caption       subject description (never claims ownership)   (required)
    group         filter key: lowers | jackets | manufacturing | details
    groupLabel    visible group name
    ratio         aspect class, e.g. "aspect-[4/5]"
    src, alt, brief               image slot (see media)
--}}
@props([
    'caption',
    'group',
    'groupLabel',
    'ratio' => 'aspect-[4/5]',
    'src',
    'alt' => '',
    'brief' => '',
])
<figure class="gallery-item" data-filter-item data-category="{{ $group }}" data-gallery-item data-caption="{{ $caption }}" data-brief="{{ $brief }}" data-reveal>
  <button type="button" class="gallery-item__btn">
    <span class="sr-only">Open image: {{ $caption }}</span>
    <x-media :src="$src" :alt="$alt" :brief="$brief" :class="$ratio" />
  </button>
  <figcaption class="gallery-item__caption"><span>{{ $caption }}</span><span>{{ $groupLabel }}</span></figcaption>
</figure>
