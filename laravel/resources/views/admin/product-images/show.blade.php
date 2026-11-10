<x-admin.layout :title="'Images · '.$product->name">
  <x-admin.page-head eyebrow="Product images" :title="$product->name" :sub="$product->category->name.' · '.($product->images->count() + ($product->image ? 1 : 0)).' images'"
                     :back="route('admin.product-images.index')" back-label="All product images">
    <a href="{{ route('admin.products.edit', $product) }}" class="adm-btn adm-btn--secondary"><i data-lucide="pencil"></i>Edit product</a>
  </x-admin.page-head>

  <div class="grid gap-8 xl:grid-cols-3">
    <section class="adm-card adm-card__body content-start">
      <h2 class="adm-section-title">Featured image</h2>
      @if ($product->image)
        <img src="{{ asset($product->image) }}" alt="{{ $product->image_alt }}" class="adm-upload__preview max-w-none">
        <p class="adm-help mt-3">{{ $product->image_alt ?: 'No description' }}</p>
      @else
        <p class="adm-help">No featured image yet. Upload one on the product form, or promote an extra image below.</p>
      @endif

      <h2 class="adm-section-title mt-10">Upload images</h2>
      <form method="POST" action="{{ route('admin.product-images.store', $product) }}" enctype="multipart/form-data" class="adm-form-grid" novalidate>
        @csrf
        <div class="adm-field @error('images') has-error @enderror @error('images.*') has-error @enderror">
          <label class="adm-label" for="f-images">Choose images</label>
          <input class="adm-file" id="f-images" name="images[]" type="file" accept="image/jpeg,image/png,image/webp" multiple required>
          <p class="adm-help">Up to 12 at once · JPG, PNG or WebP · min. 400 × 400 px · max. 5 MB each.</p>
          @error('images')<p class="adm-error">{{ $message }}</p>@enderror
          @error('images.*')<p class="adm-error">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="adm-btn adm-btn--primary"><i data-lucide="upload"></i>Upload</button>
      </form>
    </section>

    <section class="xl:col-span-2" aria-labelledby="extra-title">
      <h2 id="extra-title" class="adm-section-title">Extra images</h2>
      @if ($product->images->isEmpty())
        <div class="adm-card adm-empty"><i data-lucide="images"></i><p>No extra images yet.</p></div>
      @else
        <div class="adm-images">
          @foreach ($product->images as $image)
            <article class="adm-image-card">
              <img src="{{ asset($image->path) }}" alt="{{ $image->alt }}" loading="lazy">
              <div class="adm-image-card__body">
                <form method="POST" action="{{ route('admin.product-images.update', $image) }}" class="grid gap-2">
                  @csrf @method('PUT')
                  <label class="adm-label" for="alt-{{ $image->id }}">Description</label>
                  <input class="adm-input" id="alt-{{ $image->id }}" name="alt" value="{{ $image->alt }}" maxlength="255">
                  <label class="adm-label" for="sort-{{ $image->id }}">Order</label>
                  <input class="adm-input" id="sort-{{ $image->id }}" name="sort_order" type="number" min="0" max="9999" value="{{ $image->sort_order }}">
                  <button type="submit" class="adm-btn adm-btn--secondary adm-btn--sm"><i data-lucide="check"></i>Save</button>
                </form>
                <div class="flex gap-2">
                  <form method="POST" action="{{ route('admin.product-images.feature', $image) }}" class="flex-1">
                    @csrf
                    <button type="submit" class="adm-btn adm-btn--secondary adm-btn--sm adm-btn--block" title="Use as featured image"><i data-lucide="star"></i>Feature</button>
                  </form>
                  <form method="POST" action="{{ route('admin.product-images.destroy', $image) }}" data-confirm="Delete this image? This cannot be undone.">
                    @csrf @method('DELETE')
                    <button type="submit" class="adm-btn adm-btn--danger adm-btn--sm" title="Delete image"><i data-lucide="trash-2"></i><span class="sr-only">Delete image</span></button>
                  </form>
                </div>
              </div>
            </article>
          @endforeach
        </div>
      @endif
    </section>
  </div>
</x-admin.layout>
