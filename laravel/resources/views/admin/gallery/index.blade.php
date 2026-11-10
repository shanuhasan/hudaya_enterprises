<x-admin.layout title="Gallery">
  <x-admin.page-head eyebrow="Website" title="Gallery" sub="Images on the Gallery page. Up to five can also fill the “Inside the work” mosaic on Home.">
    <a href="{{ route('admin.gallery.create') }}" class="adm-btn adm-btn--primary"><i data-lucide="plus"></i>Add image</a>
  </x-admin.page-head>

  <div class="adm-card">
    <div class="adm-toolbar">
      <nav class="adm-tabs" aria-label="Filter by group">
        <a class="adm-tab" href="{{ route('admin.gallery.index', request()->only('q')) }}" @if (! $group) aria-current="page" @endif>All<span>{{ $total }}</span></a>
        @foreach (\App\Enums\GalleryGroup::cases() as $case)
          <a class="adm-tab" href="{{ route('admin.gallery.index', ['group' => $case->value] + request()->only('q')) }}" @if ($group === $case) aria-current="page" @endif>{{ $case->label() }}<span>{{ $counts[$case->value] ?? 0 }}</span></a>
        @endforeach
      </nav>
      <form method="GET" class="adm-search ml-auto" role="search">
        @if ($group)<input type="hidden" name="group" value="{{ $group->value }}">@endif
        <label class="sr-only" for="gallery-q">Search captions</label>
        <i data-lucide="search"></i>
        <input class="adm-input" id="gallery-q" type="search" name="q" value="{{ request('q') }}" placeholder="Search captions">
      </form>
    </div>

    @if ($items->isEmpty())
      <div class="adm-empty"><i data-lucide="images"></i><p>No gallery images here yet.</p></div>
    @else
      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead><tr><th>Image</th><th>Group</th><th>Shape</th><th>Home mosaic</th><th>Status</th><th><span class="sr-only">Actions</span></th></tr></thead>
          <tbody>
            @foreach ($items as $item)
              <tr>
                <td>
                  <div class="flex items-center gap-4">
                    <img class="adm-thumb adm-thumb--wide" src="{{ asset($item->image) }}" alt="" loading="lazy">
                    <div>
                      <a class="adm-table__primary" href="{{ route('admin.gallery.edit', $item) }}">{{ $item->caption }}</a>
                      <div class="adm-table__muted">Order {{ $item->sort_order }}</div>
                    </div>
                  </div>
                </td>
                <td>{{ $item->group->label() }}</td>
                <td>{{ $item->aspect }}</td>
                <td>@if ($item->home_position)<span class="adm-badge adm-badge--featured">Slot {{ $item->home_position }}</span>@else<span class="adm-table__muted">—</span>@endif</td>
                <td><span class="adm-badge {{ $item->is_active ? 'adm-badge--on' : 'adm-badge--off' }}">{{ $item->is_active ? 'Active' : 'Hidden' }}</span></td>
                <td>
                  <div class="adm-table__actions">
                    <a class="adm-icon-btn" href="{{ route('admin.gallery.edit', $item) }}" title="Edit"><i data-lucide="pencil"></i><span class="sr-only">Edit {{ $item->caption }}</span></a>
                    <form method="POST" action="{{ route('admin.gallery.destroy', $item) }}" data-confirm="Remove “{{ $item->caption }}” from the gallery?">
                      @csrf @method('DELETE')
                      <button type="submit" class="adm-icon-btn" title="Delete"><i data-lucide="trash-2"></i><span class="sr-only">Delete {{ $item->caption }}</span></button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      {{ $items->links('admin.partials.pagination') }}
    @endif
  </div>
</x-admin.layout>
