@php($editing = $item->exists)
<x-admin.layout :title="$editing ? 'Edit gallery image' : 'Add gallery image'">
  <x-admin.page-head eyebrow="Gallery" :title="$editing ? $item->caption : 'Add image'" :back="route('admin.gallery.index')" back-label="All gallery images" />

  <form method="POST" enctype="multipart/form-data" novalidate
        action="{{ $editing ? route('admin.gallery.update', $item) : route('admin.gallery.store') }}">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div class="grid gap-8 xl:grid-cols-3">
      <section class="adm-card adm-card__body xl:col-span-2">
        <h2 class="adm-section-title">Image</h2>
        <div class="adm-form-grid">
          <x-admin.image-input name="image_file" label="Photo" :current="$item->image" :required="! $editing" help="JPG, PNG or WebP, 400–6000 px per side, up to 5 MB." />
          <x-admin.input name="caption" label="Caption" :value="$item->caption" required maxlength="255" help="Describe the subject (e.g. “Fabric cutting”). Never claim stock photos as your own facility." />
          <x-admin.input name="image_alt" label="Image description" :value="$item->image_alt" required maxlength="255" help="What the photo shows, for screen readers." />
          <div class="adm-form-grid adm-form-grid--2">
            <x-admin.select name="group" label="Group" required :options="\App\Enums\GalleryGroup::options()" :value="$item->group" />
            <x-admin.select name="aspect" label="Shape in the grid" required :value="$item->aspect"
                            :options="['4/5' => 'Portrait 4:5', '3/4' => 'Portrait 3:4', '4/3' => 'Landscape 4:3', '1/1' => 'Square 1:1']" />
          </div>
        </div>
      </section>

      <section class="adm-card adm-card__body content-start">
        <h2 class="adm-section-title">Placement</h2>
        <div class="adm-form-grid">
          <x-admin.switch name="is_active" label="Active" help="Shown on the Gallery page." :checked="$item->is_active" />
          <x-admin.input name="sort_order" label="Order" type="number" min="0" max="9999" :value="$item->sort_order" required />
          <x-admin.select name="home_position" label="Home mosaic slot" placeholder="Not on Home" :value="$item->home_position"
                          :options="[1 => 'Slot 1 — large', 2 => 'Slot 2', 3 => 'Slot 3', 4 => 'Slot 4', 5 => 'Slot 5 — wide']" />
          <x-admin.input name="short_caption" label="Short caption for Home" :value="$item->short_caption" maxlength="60" help="Optional, e.g. “Stitching”. Uses the caption when empty." />
        </div>
      </section>
    </div>

    <div class="adm-form-actions">
      <a href="{{ route('admin.gallery.index') }}" class="adm-btn adm-btn--secondary">Cancel</a>
      <button type="submit" class="adm-btn adm-btn--primary"><i data-lucide="check"></i>{{ $editing ? 'Save changes' : 'Add to gallery' }}</button>
    </div>
  </form>

  @if ($editing)
    <form method="POST" action="{{ route('admin.gallery.destroy', $item) }}" class="mt-4 flex justify-end" data-confirm="Remove “{{ $item->caption }}” from the gallery?">
      @csrf @method('DELETE')
      <button type="submit" class="adm-btn adm-btn--danger"><i data-lucide="trash-2"></i>Delete image</button>
    </form>
  @endif
</x-admin.layout>
