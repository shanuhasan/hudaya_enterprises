<x-admin.layout title="Product images">
  <x-admin.page-head eyebrow="Catalogue" title="Product images" sub="Each product has one featured image (used on its card) and any number of extra images." />

  <div class="adm-card">
    <form method="GET" class="adm-toolbar" role="search">
      <label class="adm-search">
        <span class="sr-only">Search products</span>
        <i data-lucide="search"></i>
        <input class="adm-input" type="search" name="q" value="{{ request('q') }}" placeholder="Search products">
      </label>
      <button type="submit" class="adm-btn adm-btn--secondary">Search</button>
    </form>

    @if ($products->isEmpty())
      <div class="adm-empty"><i data-lucide="images"></i><p>No products found.</p></div>
    @else
      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead><tr><th>Product</th><th>Featured</th><th>Extra images</th><th><span class="sr-only">Actions</span></th></tr></thead>
          <tbody>
            @foreach ($products as $product)
              <tr>
                <td>
                  <a class="adm-table__primary" href="{{ route('admin.product-images.show', $product) }}">{{ $product->name }}</a>
                  <div class="adm-table__muted">{{ $product->category->name }}</div>
                </td>
                <td>
                  @if ($product->image)
                    <img class="adm-thumb" src="{{ asset($product->image) }}" alt="" loading="lazy">
                  @else
                    <span class="adm-badge adm-badge--off">Missing</span>
                  @endif
                </td>
                <td>
                  <div class="flex items-center gap-2">
                    @foreach ($product->images->take(5) as $image)
                      <img class="adm-thumb" src="{{ asset($image->path) }}" alt="" loading="lazy">
                    @endforeach
                    <span class="adm-table__muted">{{ $product->images_count }} {{ Str::plural('image', $product->images_count) }}</span>
                  </div>
                </td>
                <td>
                  <div class="adm-table__actions">
                    <a href="{{ route('admin.product-images.show', $product) }}" class="adm-btn adm-btn--secondary adm-btn--sm"><i data-lucide="image-plus"></i>Manage</a>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      {{ $products->links('admin.partials.pagination') }}
    @endif
  </div>
</x-admin.layout>
