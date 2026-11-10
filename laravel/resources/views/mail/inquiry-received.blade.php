New inquiry from the {{ config('app.name') }} website

Name:           {{ $inquiry->name }}
Company:        {{ $inquiry->company }}
Business type:  {{ $inquiry->buyer_type->label() }}
Email:          {{ $inquiry->email }}
Phone:          {{ $inquiry->phone }}
Location:       {{ collect([$inquiry->city, $inquiry->country])->filter()->implode(', ') ?: '—' }}

Category:       {{ $inquiry->category->label() }}
Product/style:  {{ $inquiry->product ?: '—' }}
Quantity:       {{ $inquiry->quantity }}
Timeline:       {{ $inquiry->timeline?->label() ?? 'Not sure yet' }}
Customisation:  {{ $inquiry->customisation ? implode(', ', $inquiry->customisation) : '—' }}

Message:
{{ $inquiry->message }}

Open in the admin panel:
{{ route('admin.inquiries.show', $inquiry) }}

Reply to this email to answer the buyer directly.
