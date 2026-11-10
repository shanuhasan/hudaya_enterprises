{{-- Converted from static-site/src/contact.html (Module 1). Data becomes dynamic in later modules. --}}
<x-layouts.app :title="'Contact & Request a Quote'" :description="'Request a quote from Hudaya Enterprises for lowers, track pants and jackets manufacturing. Share your design, quantities and timeline.'" nav-active="contact" :hide-whatsapp="true">
  <main id="main">
    <x-page-hero :crumb="'Contact'" :eyebrow="'Contact & Quote'" :title="'Start a'" :title-em="'conversation.'" :lead="'Tell us about your product, quantities and timeline. We will reply with next steps, sampling options and a quote.'" :hero-size="'compact'" :src="'images/placeholders/hero/contact.jpg'" :alt="''" :brief="'Contact hero · neatly folded black garments on a table · soft light · 16:9'" />

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

          <form class="mt-12" data-form="inquiry" data-email="{{ $site->email }}" data-whatsapp="{{ $site->whatsapp_number }}" action="#" method="post" novalidate data-reveal>
            {{-- Phase 2: action → route('inquiries.store'), add @csrf --}}

            <fieldset class="form-fieldset">
              <legend class="form-legend">Your details</legend>
              <div class="form-grid">
                <div class="field">
                  <input class="field__input" id="f-name" name="name" type="text" placeholder=" " autocomplete="name" required maxlength="100" data-error="Please enter your name.">
                  <label class="field__label" for="f-name">Full name *</label>
                  <p class="field__error"></p>
                </div>
                <div class="field">
                  <input class="field__input" id="f-company" name="company" type="text" placeholder=" " autocomplete="organization" required maxlength="120" data-error="Please enter your company or brand name.">
                  <label class="field__label" for="f-company">Company / brand *</label>
                  <p class="field__error"></p>
                </div>
                <div class="field">
                  <input class="field__input" id="f-email" name="email" type="email" placeholder=" " autocomplete="email" required maxlength="150" data-error="Please enter a valid email address, e.g. name@company.com.">
                  <label class="field__label" for="f-email">Email *</label>
                  <p class="field__error"></p>
                </div>
                <div class="field">
                  <input class="field__input" id="f-phone" name="phone" type="tel" placeholder=" " autocomplete="tel" required pattern="[0-9\s\+\-\(\)]{7,20}" data-error="Please enter a valid phone or WhatsApp number.">
                  <label class="field__label" for="f-phone">Phone / WhatsApp *</label>
                  <p class="field__error"></p>
                </div>
                <div class="field">
                  <input class="field__input" id="f-country" name="country" type="text" placeholder=" " autocomplete="country-name" maxlength="80">
                  <label class="field__label" for="f-country">Country</label>
                </div>
                <div class="field">
                  <input class="field__input" id="f-city" name="city" type="text" placeholder=" " autocomplete="address-level2" maxlength="80">
                  <label class="field__label" for="f-city">City</label>
                </div>
                <div class="field field--static field--full">
                  <select class="field__input" id="f-buyer" name="buyer_type" required data-error="Please choose your business type.">
                    <option value="" selected disabled>Select…</option>
                    <option value="brand">Clothing brand</option>
                    <option value="private-label">Private label brand</option>
                    <option value="wholesaler">Wholesaler</option>
                    <option value="retailer">Retailer</option>
                    <option value="fashion-business">Fashion business</option>
                    <option value="bulk-buyer">Bulk buyer</option>
                    <option value="other">Other</option>
                  </select>
                  <label class="field__label" for="f-buyer">Business type *</label>
                  <p class="field__error"></p>
                </div>
              </div>
            </fieldset>

            <fieldset class="form-fieldset mt-14">
              <legend class="form-legend">Your requirement</legend>
              <div class="form-grid">
                <div class="field field--static">
                  <select class="field__input" id="f-category" name="category" required data-error="Please choose a product category.">
                    <option value="" selected disabled>Select…</option>
                    <option value="lowers">Lowers &amp; track pants</option>
                    <option value="jackets">Jackets</option>
                    <option value="both">Both</option>
                    <option value="custom">Custom / not sure yet</option>
                  </select>
                  <label class="field__label" for="f-category">Product category *</label>
                  <p class="field__error"></p>
                </div>
                <div class="field">
                  <input class="field__input" id="f-product" name="product" type="text" placeholder=" " maxlength="120">
                  <label class="field__label" for="f-product">Product / style</label>
                </div>
                <div class="field">
                  <input class="field__input" id="f-quantity" name="quantity" type="text" placeholder=" " required maxlength="60" data-error="Please give an estimated quantity, e.g. 500 pieces per style.">
                  <label class="field__label" for="f-quantity">Estimated quantity *</label>
                  <p class="field__error"></p>
                </div>
                <div class="field field--static">
                  <select class="field__input" id="f-timeline" name="timeline">
                    <option value="" selected>Not sure yet</option>
                    <option value="asap">As soon as possible</option>
                    <option value="1-month">Within 1 month</option>
                    <option value="1-3-months">1–3 months</option>
                    <option value="3-plus-months">More than 3 months</option>
                  </select>
                  <label class="field__label" for="f-timeline">Target timeline</label>
                </div>
              </div>

              <div class="mt-10">
                <p class="form-legend" id="custom-label">Customisation needed</p>
                <div class="check-grid" role="group" aria-labelledby="custom-label">
                  <label class="check"><input type="checkbox" name="customisation[]" value="Private label / branding"> Private label / branding</label>
                  <label class="check"><input type="checkbox" name="customisation[]" value="Custom fabric"> Custom fabric</label>
                  <label class="check"><input type="checkbox" name="customisation[]" value="Custom colours"> Custom colours</label>
                  <label class="check"><input type="checkbox" name="customisation[]" value="Custom sizing / fit"> Custom sizing / fit</label>
                  <label class="check"><input type="checkbox" name="customisation[]" value="Custom trims"> Custom trims</label>
                  <label class="check"><input type="checkbox" name="customisation[]" value="Packing requirements"> Packing requirements</label>
                </div>
              </div>

              <div class="field mt-10">
                <textarea class="field__input" id="f-message" name="message" placeholder=" " rows="5" required minlength="20" maxlength="2000" data-error="Please describe your requirement (at least 20 characters)."></textarea>
                <label class="field__label" for="f-message">Tell us about your requirement *</label>
                <div class="flex justify-between gap-4">
                  <p class="field__hint">Style, fabric, sizes, colours, branding, references — anything that helps.</p>
                  <p class="field__hint shrink-0 tabular-nums" data-count-for="f-message" aria-hidden="true"></p>
                </div>
                <p class="field__error"></p>
              </div>
            </fieldset>

            {{-- Honeypot: hidden from people, filled by bots --}}
            <div class="sr-only" aria-hidden="true">
              <label for="f-website">Website</label>
              <input id="f-website" name="website" type="text" tabindex="-1" autocomplete="off">
            </div>

            <div class="field mt-10">
              <label class="check">
                <input type="checkbox" name="consent" required data-error="Please agree so we can reply to your inquiry.">
                I agree to {{ $site->name }} contacting me about this inquiry. *
              </label>
              <p class="field__error"></p>
            </div>

            <button type="submit" class="btn btn-dark btn-lg mt-10">
              Send Inquiry
              <i data-lucide="arrow-right"></i>
            </button>
          </form>

          {{-- Static phase: hand-off to email / WhatsApp. Phase 2 replaces this with a server-side thank-you. --}}
          <div class="form-success mt-12" data-form-success hidden tabindex="-1" role="status">
            <span class="form-success__icon"><i data-lucide="check"></i></span>
            <h3 class="t-h3">Your inquiry is ready to send.</h3>
            <p class="t-body">Choose how you would like to send it. Your details are added to the message automatically.</p>
            <p class="placeholder-note" data-send-missing hidden>[The company email and WhatsApp number are not set yet — add them in src/site.config.json to enable sending.]</p>
            <div class="flex flex-col gap-3 sm:flex-row">
              <a class="btn btn-dark" data-send-email>
                <i data-lucide="mail"></i>
                Send by Email
              </a>
              <a class="btn btn-outline-dark" data-send-whatsapp>
                @include('icons.brand.whatsapp')
                Send on WhatsApp
              </a>
            </div>
            <button type="button" class="link-arrow justify-self-start" data-form-edit>Edit inquiry</button>
          </div>
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
