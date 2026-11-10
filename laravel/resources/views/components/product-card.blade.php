{{--
  Component: product-card — enquiry-led product card (no price).
  Props:
    product   App\Models\Product (with category + types loaded)          (required)
    filter    space-separated filter keys for [data-collection-filter];
              defaults to the product's type slugs (category pages).
              Home / Products pass the category slug instead.          (optional)
  The title links to the category page; "Enquire" opens the quote form pre-filled with
  the product and category.
--}}
@props(['product', 'filter' => null])
@php
    $category = $product->category;
    $filter ??= $product->filterKeys();
@endphp
<article class="product-card" data-filter-item data-category="{{ $filter }}" data-search="{{ $product->name }} {{ $category->name }} {{ $product->type_label }}" data-reveal>
  <x-media :src="$product->image" :alt="$product->image_alt ?: $product->name" :brief="$product->name" class="product-card__media" />
  <div class="product-card__body">
    <p class="t-meta">{{ $category->name }}@if ($product->type_label) · {{ $product->type_label }}@endif</p>
    <h3 class="product-card__title"><a href="{{ route('products.category', $category) }}">{{ $product->name }}</a></h3>
    <a href="{{ route('contact', ['product' => $product->name, 'category' => $category->slug]) }}#quote" class="link-arrow">
      Enquire<span class="sr-only"> about {{ $product->name }}</span>
      <i data-lucide="arrow-right"></i>
    </a>
  </div>
</article>
