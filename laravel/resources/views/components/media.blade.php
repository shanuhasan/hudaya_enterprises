{{--
  Component: media — replaceable image slot.
  Props:
    src       path under public/, e.g. "images/placeholders/lowers/track-pant.jpg"  (required)
    alt       describes the SUBJECT, never claims ownership                       (required)
    brief     shot description shown until the photo exists                      (required)
    class     extra classes on the wrapper (aspect ratio, etc.)                  (optional)
    imgClass  extra classes on the <img>                                         (optional)
    loading   "lazy" (default) | "eager" for above-the-fold images               (optional)
    priority  "auto" (default) | "high" for the hero / LCP image                 (optional)
  A missing file renders only the placeholder (no 404). A "name@800.jpg" copy adds srcset.
  Uses <span> elements so the slot is valid inside links and buttons.
--}}
@props([
    'src',
    'alt' => '',
    'brief' => '',
    'class' => null,
    'imgClass' => null,
    'loading' => 'lazy',
    'priority' => 'auto',
])
@php($image = \App\Support\ImageSlot::resolve($src))
<span class="media {{ $class }}">
  <span class="media-ph" aria-hidden="true" data-slot-src="{{ $src }}">
    <span class="media-ph__label"><i data-lucide="image"></i>{{ $brief }}</span>
  </span>
  @if ($image['exists'])
  <img data-slot @if ($image['srcset']) srcset="{{ $image['srcset'] }}" sizes="{{ $image['sizes'] }}" @endif src="{{ asset($src) }}" alt="{{ $alt }}" class="{{ $imgClass }}" loading="{{ $loading }}" fetchpriority="{{ $priority }}" decoding="async" onerror="this.remove()">
  @endif
</span>
