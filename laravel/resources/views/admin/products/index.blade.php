<x-admin.layout title="Products">
  <x-admin.page-head eyebrow="Catalogue" title="Products" sub="Example styles shown on the category pages. Featured products also appear on Home and Products.">
    <a href="{{ route('admin.products.create') }}" class="adm-btn adm-btn--primary"><i data-lucide="plus"></i>Add product</a>
  </x-admin.page-head>

  <div class="adm-card">
    <form method="GET" class="adm-toolbar" role="search">
      <label class="adm-search">
        <span class="sr-only">Search products</span>
        <i data-lucide="search"></i>
        <input class="adm-input" type="search" name="q" value="{{ request('q') }}" placeholder="Search name, slug or material">
      </label>
      <label class="sr-only" for="filter-category">Category</label>
      <select id="filter-category" class="adm-select w-auto" name="category" data-autosubmit>
        <option value="">All categories</option>
        @foreach ($categories as $id => $name)
          <option value="{{ $id }}" @selected(request('category') == $id)>{{ $name }}</option>
        @endforeach
      </select>
      <label class="sr-only" for="filter-status">Status</label>
      <select id="filter-status" class="adm-select w-auto" name="status" data-autosubmit>
        <option value="">Any status</option>
        <option value="active" @selected(request('status') === 'active')>Active</option>
        <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
        <option value="featured" @selected(request('status') === 'featured')>Featured</option>
      </select>
      <button type="submit" class="adm-btn adm-btn--secondary">Filter</button>
      @if (request()->hasAny(['q', 'category', 'status']))
        <a href="{{ route('admin.products.index') }}" class="adm-btn adm-btn--danger">Clear</a>
      @endif
    </form>

    @if ($products->isEmpty())
      <div class="adm-empty"><i data-lucide="package"></i><p>No products match these filters.</p></div>
    @else
      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead>
            <tr><th>Product</th><th>Category</th><th>MOQ</th><th>Images</th><th>Status</th><th><span class="sr-only">Actions</span></th></tr>
          </thead>
          <tbody>
            @foreach ($products as $product)
              <tr>
                <td>
                  <div class="flex items-center gap-4">
                    @if ($product->image)
                      <img class="adm-thumb" src="{{ asset($product->image) }}" alt="" loading="lazy">
                    @else
                      <span class="adm-thumb grid place-items-center"><i data-lucide="image-plus"></i></span>
                    @endif
                    <div>
                      <a class="adm-table__primary" href="{{ route('admin.products.edit', $product) }}">{{ $product->name }}</a>
                      <div class="adm-table__muted">/{{ $product->slug }}</div>
                    </div>
                  </div>
                </td>
                <td>{{ $product->category->name }}<div class="adm-table__muted">{{ $product->type_label }}</div></td>
                <td>{{ $product->moq ?: '—' }}</td>
                <td><a href="{{ route('admin.product-images.show', $product) }}" class="hover:underline">{{ $product->images_count + ($product->image ? 1 : 0) }}</a></td>
                <td>
                  <div class="flex flex-wrap gap-2">
                    <span class="adm-badge {{ $product->is_active ? 'adm-badge--on' : 'adm-badge--off' }}">{{ $product->is_active ? 'Active' : 'Inactive' }}</span>
                    @if ($product->isFeatured())<span class="adm-badge adm-badge--featured">Featured #{{ $product->featured_position }}</span>@endif
                  </div>
                </td>
                <td>
                  <div class="adm-table__actions">
                    <a class="adm-icon-btn" href="{{ route('admin.products.edit', $product) }}" title="Edit"><i data-lucide="pencil"></i><span class="sr-only">Edit {{ $product->name }}</span></a>
                    <a class="adm-icon-btn" href="{{ route('admin.product-images.show', $product) }}" title="Images"><i data-lucide="images"></i><span class="sr-only">Images of {{ $product->name }}</span></a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" data-confirm="Delete “{{ $product->name }}” and its images? This cannot be undone.">
                      @csrf @method('DELETE')
                      <button type="submit" class="adm-icon-btn" title="Delete"><i data-lucide="trash-2"></i><span class="sr-only">Delete {{ $product->name }}</span></button>
                    </form>
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
