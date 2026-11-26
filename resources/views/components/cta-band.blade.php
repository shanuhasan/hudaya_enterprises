{{--
  Component: cta-band — closing call to action used at the end of most pages.
  Props (all optional): eyebrow, title, titleEm, text
--}}
@props([
    'eyebrow' => 'Start a project',
    'title' => 'Your vision.',
    'titleEm' => 'Our craftsmanship.',
    'text' => 'Share your design, quantity and timeline. We will respond with sampling options and a production quote.',
])
<section class="surface-ink-2 section-y rule-top" aria-labelledby="cta-band-title">
  <div class="container-site">
    <div class="section-head section-head--center max-w-4xl" data-reveal>
      <p class="eyebrow">{{ $eyebrow }}</p>
      <h2 id="cta-band-title" class="cta-band__title">{{ $title }} <span class="t-em">{{ $titleEm }}</span></h2>
      <p class="t-lead mx-auto">
        {{ $text }}
      </p>
      <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:justify-center">
        <a href="{{ route('contact') }}#quote" class="btn btn-primary">
          Request a Quote
          <i data-lucide="arrow-right"></i>
        </a>
        @if ($site->whatsapp_url)
        <a href="{{ $site->whatsapp_url }}" target="_blank" rel="noopener" class="btn btn-secondary">
          @include('icons.brand.whatsapp')
          WhatsApp Us<span class="sr-only"> (opens in a new tab)</span>
        </a>
        @else
        <a href="{{ route('contact') }}" class="btn btn-secondary">Contact Us</a>
        @endif
      </div>
    </div>
  </div>
</section>
