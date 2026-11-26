<x-admin.layout :title="'Inquiry from '.$inquiry->name">
  <x-admin.page-head eyebrow="Inquiry" :title="$inquiry->name" :sub="$inquiry->company.' · received '.$inquiry->created_at->format('d M Y, H:i')"
                     :back="route('admin.inquiries.index')" back-label="All inquiries">
    @if ($previous)<a href="{{ route('admin.inquiries.show', $previous) }}" class="adm-btn adm-btn--secondary adm-btn--sm"><i data-lucide="chevron-left"></i>Newer</a>@endif
    @if ($next)<a href="{{ route('admin.inquiries.show', $next) }}" class="adm-btn adm-btn--secondary adm-btn--sm">Older<i data-lucide="chevron-right"></i></a>@endif
  </x-admin.page-head>

  <div class="grid gap-8 xl:grid-cols-3">
    <div class="grid gap-8 xl:col-span-2 content-start">
      <section class="adm-card" aria-labelledby="req-title">
        <div class="adm-card__head"><h2 id="req-title" class="adm-card__title">Requirement</h2><x-admin.status-badge :status="$inquiry->status" /></div>
        <div class="adm-card__body">
          <dl class="adm-dl">
            <div><dt>Category</dt><dd>{{ $inquiry->category->label() }}</dd></div>
            <div><dt>Product / style</dt><dd>{{ $inquiry->product ?: '—' }}</dd></div>
            <div><dt>Estimated quantity</dt><dd>{{ $inquiry->quantity }}</dd></div>
            <div><dt>Timeline</dt><dd>{{ $inquiry->timeline?->label() ?? 'Not sure yet' }}</dd></div>
            <div><dt>Customisation</dt><dd>{{ $inquiry->customisation ? implode(', ', $inquiry->customisation) : '—' }}</dd></div>
          </dl>
          <h3 class="adm-section-title mt-8">Message</h3>
          <p class="adm-message">{{ $inquiry->message ?: '—' }}</p>
        </div>
      </section>

      <section class="adm-card" aria-labelledby="buyer-title">
        <div class="adm-card__head"><h2 id="buyer-title" class="adm-card__title">Buyer</h2></div>
        <div class="adm-card__body">
          <dl class="adm-dl">
            <div><dt>Name</dt><dd>{{ $inquiry->name }}</dd></div>
            <div><dt>Company</dt><dd>{{ $inquiry->company }}</dd></div>
            <div><dt>Business type</dt><dd>{{ $inquiry->buyer_type->label() }}</dd></div>
            <div><dt>Email</dt><dd><a class="underline underline-offset-2" href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></dd></div>
            <div><dt>Phone / WhatsApp</dt><dd><a class="underline underline-offset-2" href="tel:{{ preg_replace('/[^\d+]/', '', $inquiry->phone) }}">{{ $inquiry->phone }}</a></dd></div>
            <div><dt>Location</dt><dd>{{ collect([$inquiry->city, $inquiry->country])->filter()->implode(', ') ?: '—' }}</dd></div>
          </dl>
        </div>
      </section>

      <section class="adm-card" aria-labelledby="meta-title">
        <div class="adm-card__head"><h2 id="meta-title" class="adm-card__title">Technical details</h2></div>
        <div class="adm-card__body">
          <dl class="adm-dl">
            <div><dt>Sent from</dt><dd>{{ $inquiry->source_url ?: '—' }}</dd></div>
            <div><dt>IP address</dt><dd>{{ $inquiry->ip_address ?: '—' }}</dd></div>
            <div><dt>First read</dt><dd>{{ $inquiry->read_at?->format('d M Y, H:i') ?? '—' }}</dd></div>
          </dl>
        </div>
      </section>
    </div>

    <div class="grid gap-8 content-start">
      <section class="adm-card" aria-labelledby="status-title">
        <div class="adm-card__head"><h2 id="status-title" class="adm-card__title">Status &amp; notes</h2></div>
        <form method="POST" action="{{ route('admin.inquiries.update', $inquiry) }}" class="adm-card__body adm-form-grid">
          @csrf @method('PUT')
          <x-admin.select name="status" label="Status" required :options="\App\Enums\InquiryStatus::options()" :value="$inquiry->status" />
          <x-admin.textarea name="admin_notes" label="Internal notes" :value="$inquiry->admin_notes" rows="6" maxlength="5000" help="Only visible to admins." />
          <button type="submit" class="adm-btn adm-btn--primary"><i data-lucide="check"></i>Save</button>
        </form>
      </section>

      <section class="adm-card adm-card__body" aria-label="Reply">
        <h2 class="adm-section-title">Reply</h2>
        <div class="grid gap-2">
          <a class="adm-btn adm-btn--secondary adm-btn--block" href="mailto:{{ $inquiry->email }}?subject={{ rawurlencode('Your inquiry to '.$site->name) }}"><i data-lucide="mail"></i>Reply by email</a>
          @php($wa = preg_replace('/\D/', '', $inquiry->phone))
          @if (strlen($wa) >= 10)
            <a class="adm-btn adm-btn--secondary adm-btn--block" href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener"><i data-lucide="message-square"></i>Open in WhatsApp</a>
          @endif
        </div>
        <p class="adm-help mt-3">After replying, set the status to “Replied”.</p>
      </section>

      <form method="POST" action="{{ route('admin.inquiries.destroy', $inquiry) }}" data-confirm="Delete the inquiry from {{ $inquiry->name }}? This cannot be undone.">
        @csrf @method('DELETE')
        <button type="submit" class="adm-btn adm-btn--danger adm-btn--block"><i data-lucide="trash-2"></i>Delete inquiry</button>
      </form>
    </div>
  </div>
</x-admin.layout>
