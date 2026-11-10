<x-admin.layout title="Dashboard">
  <x-admin.page-head eyebrow="Overview" title="Dashboard" :sub="'Welcome back, '.auth()->user()->name.'. Here is what is happening with '.$site->name.'.'">
    <a href="{{ route('admin.products.create') }}" class="adm-btn adm-btn--primary"><i data-lucide="plus"></i>Add product</a>
  </x-admin.page-head>

  <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
    <a href="{{ route('admin.products.index') }}" class="adm-stat">
      <span class="adm-stat__label">Total products <i data-lucide="package"></i></span>
      <span class="adm-stat__value block">{{ $stats['products'] }}</span>
      <span class="adm-stat__note block">{{ $stats['activeProducts'] }} active · {{ $stats['featuredProducts'] }} featured</span>
    </a>
    <a href="{{ route('admin.categories.index') }}" class="adm-stat">
      <span class="adm-stat__label">Total categories <i data-lucide="folder-tree"></i></span>
      <span class="adm-stat__value block">{{ $stats['categories'] }}</span>
      <span class="adm-stat__note block">{{ $stats['activeCategories'] }} shown on the website</span>
    </a>
    <a href="{{ route('admin.inquiries.index') }}" class="adm-stat">
      <span class="adm-stat__label">Total inquiries <i data-lucide="inbox"></i></span>
      <span class="adm-stat__value block">{{ $stats['inquiries'] }}</span>
      <span class="adm-stat__note block">{{ $stats['inquiriesThisMonth'] }} this month</span>
    </a>
    <a href="{{ route('admin.inquiries.index', ['status' => 'new']) }}" class="adm-stat adm-stat--accent">
      <span class="adm-stat__label">Pending inquiries <i data-lucide="clock"></i></span>
      <span class="adm-stat__value block">{{ $stats['pending'] }}</span>
      <span class="adm-stat__note block">{{ $stats['pending'] === 1 ? 'New inquiry waiting for a reply' : 'New inquiries waiting for a reply' }}</span>
    </a>
  </div>

  <div class="mt-8 grid gap-8 xl:grid-cols-3">
    <section class="adm-card xl:col-span-2" aria-labelledby="recent-title">
      <div class="adm-card__head">
        <h2 id="recent-title" class="adm-card__title">Recent inquiries</h2>
        <a href="{{ route('admin.inquiries.index') }}" class="adm-btn adm-btn--secondary adm-btn--sm">View all <i data-lucide="arrow-right"></i></a>
      </div>
      @if ($recent->isEmpty())
        <div class="adm-empty"><i data-lucide="inbox"></i><p>No inquiries yet. They appear here as soon as a buyer sends the quote form.</p></div>
      @else
        <div class="adm-table-wrap">
          <table class="adm-table">
            <thead><tr><th>From</th><th>Category</th><th>Quantity</th><th>Received</th><th>Status</th></tr></thead>
            <tbody>
              @foreach ($recent as $inquiry)
                <tr @class(['is-unread' => $inquiry->status === \App\Enums\InquiryStatus::New])>
                  <td>
                    <a class="adm-table__primary" href="{{ route('admin.inquiries.show', $inquiry) }}">{{ $inquiry->name }}</a>
                    <div class="adm-table__muted">{{ $inquiry->company }}</div>
                  </td>
                  <td>{{ $inquiry->category->label() }}</td>
                  <td>{{ $inquiry->quantity }}</td>
                  <td class="adm-table__muted" title="{{ $inquiry->created_at->format('d M Y, H:i') }}">{{ $inquiry->created_at->diffForHumans() }}</td>
                  <td><x-admin.status-badge :status="$inquiry->status" /></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </section>

    <section class="adm-card" aria-labelledby="status-title">
      <div class="adm-card__head"><h2 id="status-title" class="adm-card__title">Inquiries by status</h2></div>
      <div class="adm-card__body">
        <dl class="adm-dl">
          @foreach ($statuses as $status)
            <div>
              <dt><a href="{{ route('admin.inquiries.index', ['status' => $status->value]) }}" class="hover:underline">{{ $status->label() }}</a></dt>
              <dd class="text-right font-serif text-2xl leading-none">{{ $statusCounts[$status->value] ?? 0 }}</dd>
            </div>
          @endforeach
        </dl>
        <p class="adm-section-title mt-8">Quick links</p>
        <div class="grid gap-2">
          <a href="{{ route('admin.gallery.create') }}" class="adm-btn adm-btn--secondary adm-btn--block"><i data-lucide="images"></i>Add gallery image ({{ $stats['gallery'] }})</a>
          <a href="{{ route('admin.settings.edit') }}" class="adm-btn adm-btn--secondary adm-btn--block"><i data-lucide="building-2"></i>Company settings</a>
        </div>
      </div>
    </section>
  </div>
</x-admin.layout>
