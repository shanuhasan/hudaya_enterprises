{{--
  Component: type-card — image card for a product type or a process step.
  Props:
    index, title, text            (required)
    src, alt, brief               image slot (see media)
    filterKey                     optional — adds a "View styles" link that filters the
                                  product grid on the same page ([data-filter-link])
--}}
@props([
    'index',
    'title',
    'text',
    'src',
    'alt' => '',
    'brief' => '',
    'filterKey' => null,
])
<article class="type-card" data-reveal>
  <x-media :src="$src" :alt="$alt" :brief="$brief" class="type-card__media" />
  <p class="t-meta mt-2">{{ $index }}</p>
  <h3 class="type-card__title">{{ $title }}</h3>
  <p class="type-card__text">{{ $text }}</p>
  @if ($filterKey)
  <a href="#styles" class="link-arrow" data-filter-link="{{ $filterKey }}">
    View styles<span class="sr-only">: {{ $title }}</span>
    <i data-lucide="arrow-right"></i>
  </a>
  @endif
</article>
