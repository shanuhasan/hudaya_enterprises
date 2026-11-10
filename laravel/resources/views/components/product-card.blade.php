{{--
  Component: product-card — enquiry-led product card (no price).
  Props:
    name      product / style name                                     (required)
    category  "Lowers" | "Jackets"                                     (required)
    type      sub-type label, e.g. "Men's · Sports"                    (required)
    filter    space-separated filter keys, e.g. "lowers" or "mens sports"   (required)
    page      category slug: "lowers" | "jackets" (defaults to filter) (optional)
    src, alt, brief                                                    image slot (see media)
  The title links to the category page; "Enquire" opens the quote form pre-filled with
  the product and category. Module 4: <x-product-card :product="$product" />.
--}}
@props([
    'name',
    'category',
    'type',
    'filter',
    'page' => null,
    'src',
    'alt' => '',
    'brief' => '',
])
@php($slug = $page ?: $filter)
<article class="product-card" data-filter-item data-category="{{ $filter }}" data-search="{{ $name }} {{ $category }} {{ $type }}" data-reveal>
  <x-media :src="$src" :alt="$alt" :brief="$brief" class="product-card__media" />
  <div class="product-card__body">
    <p class="t-meta">{{ $category }} · {{ $type }}</p>
    <h3 class="product-card__title"><a href="{{ route('products.category', $slug) }}">{{ $name }}</a></h3>
    <a href="{{ route('contact', ['product' => $name, 'category' => $slug]) }}#quote" class="link-arrow">
      Enquire<span class="sr-only"> about {{ $name }}</span>
      <i data-lucide="arrow-right"></i>
    </a>
  </div>
</article>
