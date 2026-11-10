@php
    $editing = $category->exists;
    $specs = old('specs', $category->specs ?? []);
    $specs = array_pad(array_values($specs), max(count($specs) + 2, 8), ['label' => '', 'value' => '']);
@endphp
<x-admin.layout :title="$editing ? 'Edit '.$category->name : 'Add category'">
  <x-admin.page-head :eyebrow="$editing ? 'Edit category' : 'New category'" :title="$editing ? $category->name : 'Add category'"
                     :back="route('admin.categories.index')" back-label="All categories" />

  <form method="POST" enctype="multipart/form-data" novalidate
        action="{{ $editing ? route('admin.categories.update', $category) : route('admin.categories.store') }}">
    @csrf
    @if ($editing) @method('PUT') @endif

    <div class="grid gap-8 xl:grid-cols-3">
      <div class="grid gap-8 xl:col-span-2 content-start">
        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Basics</h2>
          <div class="adm-form-grid adm-form-grid--2">
            <x-admin.input name="name" label="Name" :value="$category->name" required maxlength="80" help="Short name used on product cards, e.g. “Lowers”." data-slug-source="#f-slug" />
            <x-admin.input name="slug" label="URL slug" :value="$category->slug" maxlength="80" help="Used in the page address /products/slug and in the quote form." />
          </div>
        </section>

        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Tile on Home &amp; showcase on Products</h2>
          <div class="adm-form-grid">
            <div class="adm-form-grid adm-form-grid--2">
              <x-admin.input name="title" label="Title" :value="$category->title" required help="e.g. “Lowers &”" />
              <x-admin.input name="title_em" label="Title — italic part" :value="$category->title_em" help="e.g. “Track Pants”" />
            </div>
            <x-admin.textarea name="summary" label="Summary" :value="$category->summary" rows="3" maxlength="1000" help="Paragraph in the Products page showcase." />
            <x-admin.textarea name="highlights_text" label="Highlights" :value="implode(PHP_EOL, $category->highlights ?? [])" rows="4" help="One per line — the short list under the Home tile (max. 8)." />
            @error('highlights.*')<p class="adm-error">{{ $message }}</p>@enderror
          </div>
        </section>

        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Category page — hero</h2>
          <div class="adm-form-grid">
            <x-admin.input name="hero_eyebrow" label="Eyebrow" :value="$category->hero_eyebrow" help="e.g. “Collection 01”" />
            <div class="adm-form-grid adm-form-grid--2">
              <x-admin.input name="hero_title" label="Headline" :value="$category->hero_title" required />
              <x-admin.input name="hero_title_em" label="Headline — italic part" :value="$category->hero_title_em" />
            </div>
            <x-admin.textarea name="hero_lead" label="Lead text" :value="$category->hero_lead" rows="2" maxlength="500" />
          </div>
        </section>

        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Category page — introduction</h2>
          <div class="adm-form-grid">
            <div class="adm-form-grid adm-form-grid--2">
              <x-admin.input name="intro_title" label="Heading" :value="$category->intro_title" required />
              <x-admin.input name="intro_title_em" label="Heading — italic part" :value="$category->intro_title_em" />
            </div>
            <x-admin.textarea name="intro_text" label="Text" :value="$category->intro_text" rows="3" maxlength="2000" />
            <div class="adm-form-grid adm-form-grid--2">
              <x-admin.select name="intro_image_position" label="Image position" required :options="['right' => 'Right of the text', 'left' => 'Left of the text']" :value="$category->intro_image_position" />
              <x-admin.input name="intro_image_alt" label="Image description" :value="$category->intro_image_alt" />
            </div>
          </div>
        </section>

        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Category page — types &amp; details</h2>
          <div class="adm-form-grid">
            <div class="adm-form-grid adm-form-grid--2">
              <x-admin.input name="types_title" label="Types section heading" :value="$category->types_title" required help="e.g. “Six ways to”" />
              <x-admin.input name="types_title_em" label="Heading — italic part" :value="$category->types_title_em" help="e.g. “wear it.”" />
            </div>
            <fieldset>
              <legend class="adm-label">Details you can specify <small>(leave rows empty to remove them)</small></legend>
              <div class="mt-3 grid gap-2">
                @foreach ($specs as $i => $row)
                  <div class="grid gap-2 md:grid-cols-[12rem_1fr]">
                    <label class="sr-only" for="spec-{{ $i }}-label">Detail {{ $i + 1 }} name</label>
                    <input class="adm-input" id="spec-{{ $i }}-label" name="specs[{{ $i }}][label]" value="{{ $row['label'] ?? '' }}" placeholder="e.g. Fabric" maxlength="60">
                    <label class="sr-only" for="spec-{{ $i }}-value">Detail {{ $i + 1 }} value</label>
                    <input class="adm-input" id="spec-{{ $i }}-value" name="specs[{{ $i }}][value]" value="{{ $row['value'] ?? '' }}" placeholder="e.g. Type, composition and weight" maxlength="160">
                  </div>
                @endforeach
              </div>
              @error('specs.*.label')<p class="adm-error mt-2">{{ $message }}</p>@enderror
              @error('specs.*.value')<p class="adm-error mt-2">{{ $message }}</p>@enderror
            </fieldset>
          </div>
        </section>

        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Closing call to action</h2>
          <div class="adm-form-grid">
            <div class="adm-form-grid adm-form-grid--3">
              <x-admin.input name="cta_eyebrow" label="Eyebrow" :value="$category->cta_eyebrow" />
              <x-admin.input name="cta_title" label="Heading" :value="$category->cta_title" />
              <x-admin.input name="cta_title_em" label="Heading — italic part" :value="$category->cta_title_em" />
            </div>
            <x-admin.textarea name="cta_text" label="Text" :value="$category->cta_text" rows="2" maxlength="500" />
          </div>
        </section>

        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Search engines</h2>
          <div class="adm-form-grid">
            <x-admin.input name="meta_title" label="SEO title" :value="$category->meta_title" required maxlength="70" help="Up to 70 characters. “| Hudaya Enterprises” is added automatically." />
            <x-admin.textarea name="meta_description" label="SEO description" :value="$category->meta_description" required rows="2" maxlength="160" help="Up to 160 characters." />
          </div>
        </section>
      </div>

      <div class="grid gap-8 content-start">
        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Visibility</h2>
          <div class="adm-form-grid">
            <x-admin.switch name="is_active" label="Active" help="Shown on the website." :checked="$category->is_active" />
            <x-admin.input name="sort_order" label="Order" type="number" min="0" max="999" :value="$category->sort_order" required help="1 = first. Also shown as “01”, “02”…" />
          </div>
        </section>

        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Images</h2>
          <div class="adm-form-grid">
            <x-admin.image-input name="card_image_file" label="Tile image" :current="$category->card_image" />
            <x-admin.input name="card_image_alt" label="Tile image description" :value="$category->card_image_alt" />
            <x-admin.image-input name="hero_image_file" label="Hero image" :current="$category->hero_image" help="Wide photo, at least 1600 px wide is best." />
            <x-admin.image-input name="intro_image_file" label="Introduction image" :current="$category->intro_image" />
          </div>
        </section>
      </div>
    </div>

    <div class="adm-form-actions">
      <a href="{{ route('admin.categories.index') }}" class="adm-btn adm-btn--secondary">Cancel</a>
      <button type="submit" class="adm-btn adm-btn--primary"><i data-lucide="check"></i>{{ $editing ? 'Save changes' : 'Create category' }}</button>
    </div>
  </form>

  @if ($editing)
    <section class="adm-card mt-10" aria-labelledby="types-title">
      <div class="adm-card__head">
        <div>
          <h2 id="types-title" class="adm-card__title">Product types</h2>
          <p class="adm-help">Type cards on the category page and the filter tabs above its products.</p>
        </div>
        <a href="{{ route('admin.categories.types.create', $category) }}" class="adm-btn adm-btn--primary adm-btn--sm"><i data-lucide="plus"></i>Add type</a>
      </div>
      @if ($category->types->isEmpty())
        <div class="adm-empty"><i data-lucide="tag"></i><p>No types yet.</p></div>
      @else
        <div class="adm-table-wrap">
          <table class="adm-table">
            <thead><tr><th>Order</th><th>Type</th><th>Filter tab</th><th>Status</th><th><span class="sr-only">Actions</span></th></tr></thead>
            <tbody>
              @foreach ($category->types as $type)
                <tr>
                  <td>{{ $type->sort_order }}</td>
                  <td>
                    <div class="flex items-center gap-4">
                      @if ($type->image)<img class="adm-thumb" src="{{ asset($type->image) }}" alt="" loading="lazy">@endif
                      <a class="adm-table__primary" href="{{ route('admin.types.edit', $type) }}">{{ $type->name }}</a>
                    </div>
                  </td>
                  <td>{{ $type->short_name }} <span class="adm-table__muted">({{ $type->slug }})</span></td>
                  <td><span class="adm-badge {{ $type->is_active ? 'adm-badge--on' : 'adm-badge--off' }}">{{ $type->is_active ? 'Active' : 'Inactive' }}</span></td>
                  <td>
                    <div class="adm-table__actions">
                      <a class="adm-icon-btn" href="{{ route('admin.types.edit', $type) }}" title="Edit"><i data-lucide="pencil"></i><span class="sr-only">Edit {{ $type->name }}</span></a>
                      <form method="POST" action="{{ route('admin.types.destroy', $type) }}" data-confirm="Delete the type “{{ $type->name }}”? Products stay, but lose this filter.">
                        @csrf @method('DELETE')
                        <button type="submit" class="adm-icon-btn" title="Delete"><i data-lucide="trash-2"></i><span class="sr-only">Delete {{ $type->name }}</span></button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </section>

    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="mt-6 flex justify-end" data-confirm="Delete the category “{{ $category->name }}” and its types?">
      @csrf @method('DELETE')
      <button type="submit" class="adm-btn adm-btn--danger" @disabled($category->products_count > 0) title="{{ $category->products_count > 0 ? 'Move or delete its products first' : '' }}"><i data-lucide="trash-2"></i>Delete category</button>
    </form>
  @endif
</x-admin.layout>
