@php
    $v = fn (string $key) => $settings[$key]->value ?? '';
    $h = fn (string $key) => $settings[$key]->help ?? null;
@endphp
<x-admin.layout title="Company settings">
  <x-admin.page-head eyebrow="Website" title="Company settings"
                     sub="These details appear across the website. Leave a field empty until you have the real information — empty fields are hidden or shown as a placeholder, never invented." />

  <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" novalidate>
    @csrf @method('PUT')

    <div class="grid gap-8 xl:grid-cols-3">
      <div class="grid gap-8 xl:col-span-2 content-start">
        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Company</h2>
          <div class="adm-form-grid">
            <x-admin.input name="name" label="Company name" :value="$v('name')" required maxlength="120" />
            <x-admin.textarea name="intro" label="Company description" :value="$v('intro')" rows="3" maxlength="500" :help="$h('intro')" />
            <div class="adm-form-grid adm-form-grid--2">
              <x-admin.input name="tagline" label="Tagline" :value="$v('tagline')" maxlength="200" />
              <x-admin.input name="tagline_alt" label="Secondary tagline" :value="$v('tagline_alt')" maxlength="200" />
            </div>
          </div>
        </section>

        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Contact</h2>
          <div class="adm-form-grid">
            <div class="adm-form-grid adm-form-grid--2">
              <x-admin.input name="phone" label="Contact number (as shown)" type="tel" :value="$v('phone')" maxlength="40" :help="$h('phone')" />
              <x-admin.input name="phone_href" label="Contact number (for dialling)" type="tel" :value="$v('phone_href')" maxlength="16" :help="$h('phone_href')" />
            </div>
            <x-admin.input name="email" label="Email" type="email" :value="$v('email')" maxlength="190" :help="$h('email')" />
            <div class="adm-form-grid adm-form-grid--2">
              <x-admin.input name="whatsapp_number" label="WhatsApp number" type="tel" inputmode="numeric" :value="$v('whatsapp_number')" maxlength="15" :help="$h('whatsapp_number')" />
              <x-admin.input name="whatsapp_message" label="WhatsApp opening message" :value="$v('whatsapp_message')" maxlength="300" />
            </div>
            <x-admin.textarea name="address" label="Address" :value="$v('address')" rows="2" maxlength="500" />
            <x-admin.input name="hours" label="Business hours" :value="$v('hours')" maxlength="120" help="e.g. Mon–Sat, 10:00–19:00" />
          </div>
        </section>

        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Social links</h2>
          <div class="adm-form-grid adm-form-grid--3">
            <x-admin.input name="instagram" label="Instagram" type="url" :value="$v('instagram')" placeholder="https://instagram.com/…" :help="$h('instagram')" />
            <x-admin.input name="facebook" label="Facebook" type="url" :value="$v('facebook')" placeholder="https://facebook.com/…" :help="$h('facebook')" />
            <x-admin.input name="linkedin" label="LinkedIn" type="url" :value="$v('linkedin')" placeholder="https://linkedin.com/company/…" :help="$h('linkedin')" />
          </div>
        </section>

        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Website text</h2>
          <div class="adm-form-grid">
            <div class="adm-form-grid adm-form-grid--2">
              <x-admin.input name="announcement" label="Announcement bar (desktop)" :value="$v('announcement')" maxlength="160" :help="$h('announcement')" />
              <x-admin.input name="announcement_short" label="Announcement bar (mobile)" :value="$v('announcement_short')" maxlength="80" />
            </div>
            <x-admin.textarea name="certifications" label="Certifications" :value="$v('certifications')" rows="3" maxlength="1000" :help="$h('certifications')" />
            <x-admin.input name="photo_notice" label="Stock photo notice" :value="$v('photo_notice')" maxlength="300" :help="$h('photo_notice')" />
          </div>
        </section>
      </div>

      <div class="grid gap-8 content-start">
        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Logo</h2>
          <div class="adm-form-grid">
            <x-admin.image-input name="logo_file" label="Logo" variant="logo" :current="$v('logo') ?: 'images/brand/logo-lockup-light.png'"
                                 help="Light version for the black header: PNG or WebP with a transparent background, up to 2 MB." />
            @if ($v('logo'))
              <label class="adm-check"><input type="hidden" name="remove_logo" value="0"><input type="checkbox" name="remove_logo" value="1"> <span>Remove the uploaded logo and use the supplied Hudaya logo again</span></label>
            @else
              <p class="adm-help">Currently using the supplied Hudaya Enterprises logo.</p>
            @endif
          </div>
        </section>

        <section class="adm-card adm-card__body">
          <h2 class="adm-section-title">Where these appear</h2>
          <ul class="grid gap-2 text-sm text-soft">
            <li><strong class="text-ink">Contact number, email, address, hours</strong> — footer, menu and Contact page.</li>
            <li><strong class="text-ink">WhatsApp</strong> — floating button, footer and call-to-action buttons (hidden while empty).</li>
            <li><strong class="text-ink">Social links</strong> — footer icons (hidden while empty).</li>
            <li><strong class="text-ink">Company description</strong> — footer and search-engine data.</li>
          </ul>
        </section>
      </div>
    </div>

    <div class="adm-form-actions">
      <button type="submit" class="adm-btn adm-btn--primary"><i data-lucide="check"></i>Save settings</button>
    </div>
  </form>
</x-admin.layout>
