<x-admin.layout title="Categories">
  <x-admin.page-head eyebrow="Catalogue" title="Categories" sub="Each category has its own page on the website, a tile on Home and a showcase on Products.">
    <a href="{{ route('admin.categories.create') }}" class="adm-btn adm-btn--primary"><i data-lucide="plus"></i>Add category</a>
  </x-admin.page-head>

  <div class="adm-card">
    @if ($categories->isEmpty())
      <div class="adm-empty"><i data-lucide="folder-tree"></i><p>No categories yet.</p></div>
    @else
      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead><tr><th>Order</th><th>Category</th><th>Types</th><th>Products</th><th>Status</th><th><span class="sr-only">Actions</span></th></tr></thead>
          <tbody>
            @foreach ($categories as $category)
              <tr>
                <td class="font-serif text-2xl">{{ $category->indexLabel() }}</td>
                <td>
                  <div class="flex items-center gap-4">
                    @if ($category->card_image)<img class="adm-thumb" src="{{ asset($category->card_image) }}" alt="" loading="lazy">@endif
                    <div>
                      <a class="adm-table__primary" href="{{ route('admin.categories.edit', $category) }}">{{ $category->name }}</a>
                      <div class="adm-table__muted">/products/{{ $category->slug }}</div>
                    </div>
                  </div>
                </td>
                <td>{{ $category->types_count }}</td>
                <td><a class="hover:underline" href="{{ route('admin.products.index', ['category' => $category->id]) }}">{{ $category->products_count }}</a></td>
                <td><span class="adm-badge {{ $category->is_active ? 'adm-badge--on' : 'adm-badge--off' }}">{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td>
                <td>
                  <div class="adm-table__actions">
                    <a class="adm-icon-btn" href="{{ route('admin.categories.edit', $category) }}" title="Edit"><i data-lucide="pencil"></i><span class="sr-only">Edit {{ $category->name }}</span></a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" data-confirm="Delete the category “{{ $category->name }}” and its types?">
                      @csrf @method('DELETE')
                      <button type="submit" class="adm-icon-btn" title="Delete"><i data-lucide="trash-2"></i><span class="sr-only">Delete {{ $category->name }}</span></button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      {{ $categories->links('admin.partials.pagination') }}
    @endif
  </div>
</x-admin.layout>
