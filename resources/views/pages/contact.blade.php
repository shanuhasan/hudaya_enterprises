<x-layouts.app :title="$page->meta_title" :description="$page->meta_description" nav-active="contact" :hide-whatsapp="true">
  <main id="main">
    <x-page-hero :crumb="'Contact'" :eyebrow="$page->hero_eyebrow" :title="$page->hero_title" :title-em="$page->hero_title_em" :lead="$page->hero_lead" :hero-size="'compact'" :src="$page->hero_image" :alt="$page->hero_image_alt" :brief="'Contact hero · neatly folded black garments on a table · soft light · 16:9'" />

    {{-- ============================================================ CONTACT + FORM --}}
    <section id="quote" class="surface-white section-y" aria-labelledby="quote-title">
      <div class="container-site grid gap-16 lg:grid-cols-12 lg:gap-6">

        {{-- Contact details --}}
        <aside class="lg:col-span-4" aria-labelledby="details-title">
          <div class="lg:sticky lg:top-32">
            <p class="eyebrow" data-reveal>01 / Get in touch</p>
            <h2 id="details-title" class="t-h2 mt-8" data-reveal>Talk to <span class="t-em">us directly.</span></h2>
            <p class="t-body mt-6" data-reveal>Prefer to talk first? Reach us by phone, email or WhatsApp.</p>

            <div class="mt-10 border-t border-rule pt-8" data-reveal>
              <x-contact-lines />
            </div>

            @if ($site->whatsapp_number)
            <a href="{{ $site->whatsapp_url }}" target="_blank" rel="noopener" class="btn btn-dark mt-10" data-reveal>
              @include('icons.brand.whatsapp')
              Chat on WhatsApp<span class="sr-only"> (opens in a new tab)</span>
            </a>
            @endif
          </div>
        </aside>

        {{-- Inquiry form --}}
        <div class="lg:col-span-7 lg:col-start-6">
          <p class="eyebrow" data-reveal>02 / Request a quote</p>
          <h2 id="quote-title" class="t-h2 mt-8" data-reveal>Business <span class="t-em">inquiry.</span></h2>
          <p class="t-body mt-6" data-reveal>Fields marked * are required. The more detail you share, the more accurate our reply can be.</p>

          @php
              // Server-side validation state for a field: CSS class, aria and message.
              $invalid = fn (string $field) => $errors->has($field) ? 'is-invalid' : '';
              $selected = old('category', request('category'));
              $chosen = (array) old('customisation', []);
          @endphp

          @if (session('inquiry_sent'))
          {{-- Shown after the server has saved the inquiry. --}}
          <div class="form-success mt-12" data-form-success tabindex="-1" role="status" data-reveal>
            <span class="form-success__icon"><i data-lucide="check"></i></span>
            <h3 class="t-h3">Thank you — your inquiry has been sent.</h3>
            <p class="t-body">We have received your details and will reply by email or phone with next steps.</p>
            <div class="flex flex-col gap-3 sm:flex-row">
              @if ($site->whatsapp_url)
              <a class="btn btn-dark" href="{{ $site->whatsapp_url }}" target="_blank" rel="noopener">
                @include('icons.brand.whatsapp')
                Continue on WhatsApp<span class="sr-only"> (opens in a new tab)</span>
              </a>
              @endif
              <a class="btn btn-outline-dark" href="{{ route('products.index') }}">Browse products</a>
            </div>
            <a class="link-arrow justify-self-start" href="{{ route('contact') }}#quote">Send another inquiry <i data-lucide="arrow-right"></i></a>
          </div>
          @else
          <form class="mt-12" data-form="inquiry" action="{{ route('inquiries.store') }}" method="post" novalidate data-reveal>
            @csrf

            @error('form')
            <p class="placeholder-note mb-10" role="alert">{{ $message }}</p>
            @enderror

            <fieldset class="form-fieldset">
              <legend class="form-legend">Your details</legend>
              <div class="form-grid">
                <div class="field {{ $invalid('name') }}">
                  <input class="field__input" id="f-name" name="name" type="text" placeholder=" " autocomplete="name" required maxlength="100" value="{{ old('name') }}" data-error="Please enter your name." @error('name') aria-invalid="true" aria-describedby="f-name-error" @enderror>
                  <label class="field__label" for="f-name">Full name *</label>
                  <p class="field__error" id="f-name-error">@error('name'){{ $message }}@enderror</p>
                </div>
                <div class="field {{ $invalid('company') }}">
                  <input class="field__input" id="f-company" name="company" type="text" placeholder=" " autocomplete="organization" required maxlength="120" value="{{ old('company') }}" data-error="Please enter your company or brand name." @error('company') aria-invalid="true" aria-describedby="f-company-error" @enderror>
                  <label class="field__label" for="f-company">Company / brand *</label>
                  <p class="field__error" id="f-company-error">@error('company'){{ $message }}@enderror</p>
                </div>
                <div class="field {{ $invalid('email') }}">
                  <input class="field__input" id="f-email" name="email" type="email" placeholder=" " autocomplete="email" required maxlength="150" value="{{ old('email') }}" data-error="Please enter a valid email address, e.g. name@company.com." @error('email') aria-invalid="true" aria-describedby="f-email-error" @enderror>
                  <label class="field__label" for="f-email">Email *</label>
                  <p class="field__error" id="f-email-error">@error('email'){{ $message }}@enderror</p>
                </div>
                <div class="field {{ $invalid('phone') }}">
                  <input class="field__input" id="f-phone" name="phone" type="tel" placeholder=" " autocomplete="tel" required pattern="[0-9\s\+\-\(\)]{7,20}" value="{{ old('phone') }}" data-error="Please enter a valid phone or WhatsApp number." @error('phone') aria-invalid="true" aria-describedby="f-phone-error" @enderror>
                  <label class="field__label" for="f-phone">Phone / WhatsApp *</label>
                  <p class="field__error" id="f-phone-error">@error('phone'){{ $message }}@enderror</p>
                </div>
                <div class="field">
                  <input class="field__input" id="f-country" name="country" type="text" placeholder=" " autocomplete="country-name" maxlength="80" value="{{ old('country') }}">
                  <label class="field__label" for="f-country">Country</label>
                </div>
                <div class="field">
                  <input class="field__input" id="f-city" name="city" type="text" placeholder=" " autocomplete="address-level2" maxlength="80" value="{{ old('city') }}">
                  <label class="field__label" for="f-city">City</label>
                </div>
                <div class="field field--static field--full {{ $invalid('buyer_type') }}">
                  <select class="field__input" id="f-buyer" name="buyer_type" required data-error="Please choose your business type." @error('buyer_type') aria-invalid="true" aria-describedby="f-buyer-error" @enderror>
                    <option value="" @selected(! old('buyer_type')) disabled>Select…</option>
                    @foreach ($buyerTypes as $value => $label)
                    <option value="{{ $value }}" @selected(old('buyer_type') === $value)>{{ $label }}</option>
                    @endforeach
                  </select>
                  <label class="field__label" for="f-buyer">Business type *</label>
                  <p class="field__error" id="f-buyer-error">@error('buyer_type'){{ $message }}@enderror</p>
                </div>
              </div>
            </fieldset>

            <fieldset class="form-fieldset mt-14">
              <legend class="form-legend">Your requirement</legend>
              <div class="form-grid">
                <div class="field field--static {{ $invalid('category') }}">
                  <select class="field__input" id="f-category" name="category" required data-error="Please choose a product category." @error('category') aria-invalid="true" aria-describedby="f-category-error" @enderror>
                    <option value="" @selected(! $selected) disabled>Select…</option>
                    @foreach ($inquiryCategories as $value => $label)
                    <option value="{{ $value }}" @selected($selected === $value)>{{ $label }}</option>
                    @endforeach
                  </select>
                  <label class="field__label" for="f-category">Product category *</label>
                  <p class="field__error" id="f-category-error">@error('category'){{ $message }}@enderror</p>
                </div>
                <div class="field">
                  <input class="field__input" id="f-product" name="product" type="text" placeholder=" " maxlength="120" value="{{ old('product', request('product')) }}">
                  <label class="field__label" for="f-product">Product / style</label>
                </div>
                <div class="field {{ $invalid('quantity') }}">
                  <input class="field__input" id="f-quantity" name="quantity" type="text" placeholder=" " required maxlength="60" value="{{ old('quantity') }}" data-error="Please give an estimated quantity, e.g. 500 pieces per style." @error('quantity') aria-invalid="true" aria-describedby="f-quantity-error" @enderror>
                  <label class="field__label" for="f-quantity">Estimated quantity *</label>
                  <p class="field__error" id="f-quantity-error">@error('quantity'){{ $message }}@enderror</p>
                </div>
                <div class="field field--static">
                  <select class="field__input" id="f-timeline" name="timeline">
                    <option value="" @selected(! old('timeline'))>Not sure yet</option>
                    @foreach ($timelines as $value => $label)
                    <option value="{{ $value }}" @selected(old('timeline') === $value)>{{ $label }}</option>
                    @endforeach
                  </select>
                  <label class="field__label" for="f-timeline">Target timeline</label>
                </div>
              </div>

              <div class="mt-10">
                <p class="form-legend" id="custom-label">Customisation needed</p>
                <div class="check-grid" role="group" aria-labelledby="custom-label">
                  @foreach ($customisationOptions as $option)
                  <label class="check"><input type="checkbox" name="customisation[]" value="{{ $option }}" @checked(in_array($option, $chosen, true))> {{ $option }}</label>
                  @endforeach
                </div>
              </div>

              <div class="field mt-10 {{ $invalid('message') }}">
                <textarea class="field__input" id="f-message" name="message" placeholder=" " rows="5" required minlength="20" maxlength="2000" data-error="Please describe your requirement (at least 20 characters)." @error('message') aria-invalid="true" aria-describedby="f-message-error" @enderror>{{ old('message') }}</textarea>
                <label class="field__label" for="f-message">Tell us about your requirement *</label>
                <div class="flex justify-between gap-4">
                  <p class="field__hint">Style, fabric, sizes, colours, branding, references — anything that helps.</p>
                  <p class="field__hint shrink-0 tabular-nums" data-count-for="f-message" aria-hidden="true"></p>
                </div>
                <p class="field__error" id="f-message-error">@error('message'){{ $message }}@enderror</p>
              </div>
            </fieldset>

            {{-- Honeypot: hidden from people, filled by bots --}}
            <div class="sr-only" aria-hidden="true">
              <label for="f-website">Website</label>
              <input id="f-website" name="website" type="text" tabindex="-1" autocomplete="off">
            </div>

            <div class="field mt-10 {{ $invalid('consent') }}">
              <label class="check">
                <input type="checkbox" name="consent" value="1" required @checked(old('consent')) data-error="Please agree so we can reply to your inquiry.">
                I agree to {{ $site->name }} contacting me about this inquiry. *
              </label>
              <p class="field__error">@error('consent'){{ $message }}@enderror</p>
            </div>

            <button type="submit" class="btn btn-dark btn-lg mt-10" data-submit>
              Send Inquiry
              <i data-lucide="arrow-right"></i>
            </button>
          </form>
          @endif
        </div>
      </div>
    </section>

    {{-- ============================================================ WHAT HAPPENS NEXT --}}
    <section class="surface-ink section-y" aria-labelledby="next-title">
      <div class="container-site">
        <div class="section-head" data-reveal>
          <p class="eyebrow">03 / What happens next</p>
          <h2 id="next-title" class="t-h2">From inquiry to <span class="t-em">sample.</span></h2>
        </div>
        <ol class="feature-grid mt-14" data-reveal-group>
          <li class="feature" data-reveal>
            <span class="feature__num">i.</span>
            <h3 class="feature__title">We review</h3>
            <p class="feature__text">We read your requirement and check the details needed to quote accurately.</p>
          </li>
          <li class="feature" data-reveal>
            <span class="feature__num">ii.</span>
            <h3 class="feature__title">We reply</h3>
            <p class="feature__text">We contact you by email, phone or WhatsApp with questions, options and next steps.</p>
          </li>
          <li class="feature" data-reveal>
            <span class="feature__num">iii.</span>
            <h3 class="feature__title">Sampling &amp; quote</h3>
            <p class="feature__text">Once the brief is clear, we agree sampling and share a production quote.</p>
          </li>
        </ol>
      </div>
    </section>

    {{-- ============================================================ LOCATION --}}
    <section class="surface-ink-2 section-y-compact" aria-labelledby="location-title">
      <div class="container-site grid gap-10 lg:grid-cols-12 lg:items-center lg:gap-6">
        <div class="lg:col-span-4" data-reveal>
          <p class="eyebrow">04 / Location</p>
          <h2 id="location-title" class="t-h3 mt-6">Visit or send samples</h2>
          <p class="t-body mt-4">{{ $site->address ?: '[Street address, City, State, PIN code]' }}</p>
          <p class="t-small mt-4">Please contact us before visiting so we can arrange a time.</p>
        </div>
        <div class="lg:col-span-7 lg:col-start-6" data-reveal>
          {{-- Replace with a Google Maps embed (iframe, loading="lazy") once the address is confirmed. --}}
          <x-media :class="'aspect-[16/9]'" :src="'images/placeholders/hero/location.jpg'" :alt="'Location of Hudaya Enterprises'" :brief="'Map embed or building exterior · add once address is confirmed · 16:9'" />
        </div>
      </div>
    </section>
  </main>
</x-layouts.app>
