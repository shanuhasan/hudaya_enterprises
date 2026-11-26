@php($editing = $type->exists)
<x-admin.layout :title="$editing ? 'Edit '.$type->name : 'Add type'">
  <x-admin.page-head :eyebrow="$category->name.' · product type'" :title="$editing ? $type->name : 'Add type'"
                     :back="route('admin.categories.edit', $category)" :back-label="'Back to '.$category->name" />

  <form method="POST" enctype="multipart/form-data" novalidate
        action="{{ $editing ? route('admin.types.update', $type) : route('admin.categories.types.store', $category) }}">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div class="grid gap-8 xl:grid-cols-3">
      <section class="adm-card adm-card__body xl:col-span-2">
        <h2 class="adm-section-title">Type</h2>
        <div class="adm-form-grid">
          <x-admin.input name="name" label="Card title" :value="$type->name" required maxlength="80" help="e.g. “Men's Lowers”" />
          <div class="adm-form-grid adm-form-grid--2">
            <x-admin.input name="short_name" label="Filter tab label" :value="$type->short_name" required maxlength="40" help="e.g. “Men's”" data-slug-source="#f-slug" />
            <x-admin.input name="slug" label="Filter key" :value="$type->slug" maxlength="40" help="Lowercase, e.g. “mens”." />
          </div>
          <x-admin.textarea name="description" label="Card text" :value="$type->description" rows="3" maxlength="300" />
          <div class="adm-form-grid adm-form-grid--2">
            <x-admin.input name="sort_order" label="Order" type="number" min="0" max="999" :value="$type->sort_order" required />
            <x-admin.input name="image_alt" label="Image description" :value="$type->image_alt" />
          </div>
        </div>
      </section>

      <section class="adm-card adm-card__body content-start">
        <h2 class="adm-section-title">Image &amp; visibility</h2>
        <div class="adm-form-grid">
          <x-admin.image-input name="image_file" label="Card image" :current="$type->image" />
          <x-admin.switch name="is_active" label="Active" help="Shown on the category page." :checked="$type->is_active" />
        </div>
      </section>
    </div>

    <div class="adm-form-actions">
      <a href="{{ route('admin.categories.edit', $category) }}" class="adm-btn adm-btn--secondary">Cancel</a>
      <button type="submit" class="adm-btn adm-btn--primary"><i data-lucide="check"></i>{{ $editing ? 'Save type' : 'Add type' }}</button>
    </div>
  </form>
</x-admin.layout>
