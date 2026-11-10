<x-admin.layout title="Inquiries">
  <x-admin.page-head eyebrow="Sales" title="Inquiries" sub="Quote requests sent from the website. Opening an inquiry marks it as read." />

  <div class="adm-card">
    <div class="adm-toolbar">
      <nav class="adm-tabs" aria-label="Filter by status">
        <a class="adm-tab" href="{{ route('admin.inquiries.index', request()->only('q', 'category')) }}" @if (! $status) aria-current="page" @endif>All<span>{{ $total }}</span></a>
        @foreach (\App\Enums\InquiryStatus::cases() as $case)
          <a class="adm-tab" href="{{ route('admin.inquiries.index', ['status' => $case->value] + request()->only('q', 'category')) }}" @if ($status === $case) aria-current="page" @endif>{{ $case->label() }}<span>{{ $counts[$case->value] ?? 0 }}</span></a>
        @endforeach
      </nav>
      <form method="GET" class="ml-auto flex flex-wrap gap-3" role="search">
        @if ($status)<input type="hidden" name="status" value="{{ $status->value }}">@endif
        <label class="sr-only" for="inq-category">Category</label>
        <select id="inq-category" class="adm-select w-auto" name="category" data-autosubmit>
          <option value="">All categories</option>
          @foreach (\App\Enums\InquiryCategory::options() as $value => $label)
            <option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        <label class="adm-search">
          <span class="sr-only">Search inquiries</span>
          <i data-lucide="search"></i>
          <input class="adm-input" type="search" name="q" value="{{ request('q') }}" placeholder="Name, company, email, phone">
        </label>
        <button type="submit" class="sr-only">Search</button>
      </form>
    </div>

    @if ($inquiries->isEmpty())
      <div class="adm-empty"><i data-lucide="inbox"></i><p>{{ $total ? 'No inquiries match these filters.' : 'No inquiries yet. They appear here when a buyer sends the quote form.' }}</p></div>
    @else
      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead><tr><th>From</th><th>Contact</th><th>Category</th><th>Quantity</th><th>Received</th><th>Status</th><th><span class="sr-only">Actions</span></th></tr></thead>
          <tbody>
            @foreach ($inquiries as $inquiry)
              <tr @class(['is-unread' => $inquiry->status === \App\Enums\InquiryStatus::New])>
                <td>
                  <a class="adm-table__primary" href="{{ route('admin.inquiries.show', $inquiry) }}">{{ $inquiry->name }}</a>
                  <div class="adm-table__muted">{{ $inquiry->company }} · {{ $inquiry->buyer_type->label() }}</div>
                </td>
                <td>{{ $inquiry->email }}<div class="adm-table__muted">{{ $inquiry->phone }}</div></td>
                <td>{{ $inquiry->category->label() }}@if ($inquiry->product)<div class="adm-table__muted">{{ $inquiry->product }}</div>@endif</td>
                <td>{{ $inquiry->quantity }}</td>
                <td class="adm-table__muted" title="{{ $inquiry->created_at->format('d M Y, H:i') }}">{{ $inquiry->created_at->format('d M Y') }}<div>{{ $inquiry->created_at->format('H:i') }}</div></td>
                <td><x-admin.status-badge :status="$inquiry->status" /></td>
                <td>
                  <div class="adm-table__actions">
                    <a class="adm-icon-btn" href="{{ route('admin.inquiries.show', $inquiry) }}" title="View"><i data-lucide="eye"></i><span class="sr-only">View inquiry from {{ $inquiry->name }}</span></a>
                    <form method="POST" action="{{ route('admin.inquiries.destroy', $inquiry) }}" data-confirm="Delete the inquiry from {{ $inquiry->name }}? This cannot be undone.">
                      @csrf @method('DELETE')
                      <button type="submit" class="adm-icon-btn" title="Delete"><i data-lucide="trash-2"></i><span class="sr-only">Delete inquiry from {{ $inquiry->name }}</span></button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      {{ $inquiries->links('admin.partials.pagination') }}
    @endif
  </div>
</x-admin.layout>
