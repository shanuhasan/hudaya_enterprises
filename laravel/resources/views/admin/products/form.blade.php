@php($editing = $product->exists)
<x-admin.layout :title="$editing ? 'Edit '.$product->name : 'Add product'">
  <x-admin.page-head :eyebrow="$editing ? 'Edit product' : 'New product'" :title="$editing ? $product->name : 'Add product'"
                     :back="route('admin.products.index')" back-label="All products">
    @if ($editing)
      <a href="{{ route('admin.product-images.show', $product) }}" class="adm-btn adm-btn--secondary"><i data-lucide="images"></i>Manage images</a>
    @endif
  </x-admin.page-head>

  <form method="POST" enctype="multipart/form-data" novalidate
        action="{{ $editing ? route('admin.products.update', $product) : route('admin.products.store') }}">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div class="grid gap-8 xl:grid-cols-3">
      <div class="grid gap-8 xl:col-span-2 content-start">
        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Details</h2>
          <div class="adm-form-grid">
            <div class="adm-form-grid adm-form-grid--2">
              <x-admin.input name="name" label="Product name" :value="$product->name" required maxlength="120" data-slug-source="#f-slug" />
              <x-admin.input name="slug" label="URL slug" :value="$product->slug" maxlength="140" help="Lowercase words with hyphens. Filled in from the name if left empty." />
            </div>
            <div class="adm-form-grid adm-form-grid--2">
              <x-admin.select name="category_id" label="Category" required placeholder="Choose a category…"
                              :options="$categories->pluck('name', 'id')" :value="$product->category_id" />
              <x-admin.input name="type_label" label="Type label on the card" :value="$product->type_label" maxlength="80" help="Small text above the name, e.g. “Men's · Sports”." />
            </div>
            <x-admin.textarea name="description" label="Description" :value="$product->description" rows="6" maxlength="5000" />
          </div>
        </section>

        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Specification</h2>
          <div class="adm-form-grid adm-form-grid--3">
            <x-admin.input name="material" label="Material" :value="$product->material" maxlength="160" help="e.g. Cotton-poly fleece" />
            <x-admin.input name="sizes_text" label="Available sizes" :value="old('sizes_text', implode(', ', $product->sizes ?? []))" help="Separate with commas: S, M, L, XL" />
            <x-admin.input name="moq" label="Minimum order quantity" :value="$product->moq" maxlength="80" help="e.g. 300 pieces per style" />
          </div>
          @error('sizes.*')<p class="adm-error mt-2">{{ $message }}</p>@enderror
        </section>

        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Filter types</h2>
          <p class="adm-help mb-4">Choose the filter tabs this product appears under on its category page. Only types of the selected category are saved.</p>
          @php($chosen = collect(old('types', $product->types->pluck('id')->all()))->map(fn ($id) => (int) $id))
          <div class="grid gap-6 md:grid-cols-2">
            @foreach ($categories as $category)
              <fieldset>
                <legend class="adm-label mb-3">{{ $category->name }}</legend>
                <div class="grid gap-2">
                  @forelse ($category->types as $type)
                    <label class="adm-check"><input type="checkbox" name="types[]" value="{{ $type->id }}" @checked($chosen->contains($type->id))> <span>{{ $type->name }}</span></label>
                  @empty
                    <p class="adm-help">No types yet.</p>
                  @endforelse
                </div>
              </fieldset>
            @endforeach
          </div>
          @error('types.*')<p class="adm-error mt-3">{{ $message }}</p>@enderror
        </section>

        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">More images</h2>
          <div class="adm-field @error('gallery.*') has-error @enderror">
            <label class="adm-label" for="f-gallery">Add images <small>(optional, up to 12 at once)</small></label>
            <input class="adm-file" id="f-gallery" name="gallery[]" type="file" accept="image/jpeg,image/png,image/webp" multiple>
            <p class="adm-help">JPG, PNG or WebP, up to 5 MB each. Reorder, describe or remove them under “Manage images”.</p>
            @error('gallery.*')<p class="adm-error">{{ $message }}</p>@enderror
            @error('gallery')<p class="adm-error">{{ $message }}</p>@enderror
          </div>
          @if ($editing && $product->images->isNotEmpty())
            <div class="mt-5 flex flex-wrap gap-3">
              @foreach ($product->images as $image)
                <img class="adm-thumb" src="{{ asset($image->path) }}" alt="{{ $image->alt }}" loading="lazy">
              @endforeach
            </div>
          @endif
        </section>
      </div>

      <div class="grid gap-8 content-start">
        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Featured image</h2>
          <div class="adm-form-grid">
            <x-admin.image-input name="image" label="Image" :current="$product->image" help="Shown on the product card. JPG, PNG or WebP, 400–6000 px per side, up to 5 MB." />
            <x-admin.input name="image_alt" label="Image description" :value="$product->image_alt" maxlength="255" help="Describe what the photo shows, for screen readers." />
          </div>
        </section>

        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Visibility</h2>
          <div class="adm-form-grid">
            <x-admin.switch name="is_active" label="Active" help="Shown on the website." :checked="$product->is_active" />
            <x-admin.switch name="is_featured" label="Featured" help="Shown in “Featured styles” on Home (first 8) and Products (first 4)." :checked="$product->isFeatured()" />
            <x-admin.input name="featured_position" label="Featured order" type="number" min="1" max="999" :value="$product->featured_position" help="1 = first. Left empty, a featured product goes to the end." />
            <x-admin.input name="sort_order" label="Sort order" type="number" min="0" max="9999" :value="$product->sort_order ?? 0" help="Position within its category page." />
          </div>
        </section>
      </div>
    </div>

    <div class="adm-form-actions">
      <a href="{{ route('admin.products.index') }}" class="adm-btn adm-btn--secondary">Cancel</a>
      <button type="submit" class="adm-btn adm-btn--primary"><i data-lucide="check"></i>{{ $editing ? 'Save changes' : 'Create product' }}</button>
    </div>
  </form>

  @if ($editing)
    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="mt-4 flex justify-end" data-confirm="Delete “{{ $product->name }}” and its images? This cannot be undone.">
      @csrf @method('DELETE')
      <button type="submit" class="adm-btn adm-btn--danger"><i data-lucide="trash-2"></i>Delete product</button>
    </form>
  @endif
</x-admin.layout>
