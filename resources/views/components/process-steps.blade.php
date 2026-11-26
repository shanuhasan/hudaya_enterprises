{{-- Component: process-steps — Inquiry → Dispatch (horizontal on desktop, vertical on mobile). --}}
@php
    $steps = [
        ['message-square', 'Inquiry', 'You share designs, references, quantities and timelines.'],
        ['scissors', 'Sampling', 'A sample is developed to confirm fabric, fit and finish.'],
        ['circle-check', 'Approval', 'Specifications, pricing and delivery schedule are agreed.'],
        ['layers', 'Production', 'Cutting, stitching and finishing to the approved sample.'],
        ['clipboard-check', 'Quality Check', 'Garments are inspected for stitching, measurements and finish.'],
        ['truck', 'Dispatch', 'Orders are packed and dispatched as agreed.'],
    ];
@endphp
<ol class="process" data-reveal-group>
  @foreach ($steps as [$icon, $title, $text])
  <li class="process__step" data-reveal>
    <span class="process__icon"><i data-lucide="{{ $icon }}"></i></span>
    <span class="process__num">{{ sprintf('%02d', $loop->iteration) }}</span>
    <h3 class="process__title">{{ $title }}</h3>
    <p class="process__text">{{ $text }}</p>
  </li>
  @endforeach
</ol>
