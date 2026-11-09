# Hudaya Enterprises — Architecture & Design System

> Status: **DRAFT — awaiting approval.** Nothing is implemented yet.
> Environment detected: PHP 8.2.12 · Node 22.14 · Composer 2.6.4 · XAMPP (MySQL/MariaDB) · Windows.

---

## 1. Project analysis

| Aspect | Decision |
|---|---|
| Site type | B2B manufacturing showcase + inquiry generation. **No cart, no prices, no checkout.** |
| Primary conversion | "Request a Quote" form (with category/product prefill). |
| Secondary conversions | WhatsApp click, phone call, email, contact form. |
| Trust drivers | Process transparency, craftsmanship details, quality workflow, real photography (when available). |
| Content risk | No invented certifications, capacity, clients, years, or stats. All such blocks are **data-driven and hidden when empty**. |
| Imagery risk | Placeholder photos are flagged (`is_placeholder`) and never captioned as "our factory". |

### Core user journeys
1. **Brand / private label buyer:** Home → Products → Lowers → Product → Request Quote (prefilled).
2. **Wholesaler checking capability:** Home → Manufacturing → Quality → Contact / WhatsApp.
3. **Returning buyer:** direct to Contact / WhatsApp from header/footer.

---

## 2. Development phases & module roadmap

Each module is built, tested, and approved before the next.

### Phase 1 — Static frontend (HTML5 · Tailwind CSS v4 · Vanilla JS · Lucide)
| # | Module | Output |
|---|---|---|
| 1.1 | Tooling + design tokens + base CSS + **style guide page** | `styleguide.html` showing every token/component for sign-off |
| 1.2 | Header (desktop + mobile menu) + footer | partials |
| 1.3 | Homepage | `index.html` |
| 1.4 | About | `about.html` |
| 1.5 | Products overview, Lowers, Jackets, product detail template | 4 pages |
| 1.6 | Manufacturing, Quality Assurance | 2 pages |
| 1.7 | Gallery (filter + accessible lightbox) | `gallery.html` |
| 1.8 | Contact + Request a Quote (client-side validation) | 2 pages |
| 1.9 | QA pass: responsive, a11y, Lighthouse, cross-browser | report |

### Phase 2 — Laravel 12 conversion (Blade · Tailwind v4 · Vite · MySQL)
Pixel-identical port. Same CSS file, same JS modules, same markup → Blade components.

### Phase 3 — Admin panel
Products, categories, images, gallery, inquiries, site settings, editable page content.

---

## 3. Tech decisions (and why)

| Decision | Reason |
|---|---|
| **Tailwind CSS v4 via Vite, not CDN** | Laravel 12 ships Tailwind v4 + Vite. Using the same toolchain in Phase 1 means `app.css` and JS move to Laravel **unchanged** — guaranteeing "do not change the approved UI". |
| **Vite multi-page build + tiny custom `@include` plugin (~25 lines)** | Header/footer written once. `<!-- @include partials/header.html -->` maps 1:1 to Blade `@include` / `<x-…>`. No runtime JS injection (better SEO, no flash). |
| **Lucide via npm (`lucide`), tree-shaken `createIcons`** | Only icons used are bundled. |
| **No JS framework** | Per brief. Small ES modules, each self-initialising on a `data-*` hook. |
| **Fonts via Google Fonts with `preconnect` + `display=swap`** | Cormorant Garamond 400/500/600 (+ italic 400), Inter 400/500/600. |
| **Custom admin (Blade + vanilla JS), not Filament** *(recommendation, see §11)* | Keeps one stack, no Livewire, full control of look. Filament is faster to build if you prefer. |

---

## 4. Folder structure

### Phase 1
```
hudaya_enterprises/
├─ docs/ARCHITECTURE.md
└─ static-site/
   ├─ package.json
   ├─ vite.config.js              # multi-page inputs + include plugin
   ├─ plugins/html-include.js
   ├─ src/
   │  ├─ index.html
   │  ├─ about.html
   │  ├─ products.html
   │  ├─ products/lowers.html
   │  ├─ products/jackets.html
   │  ├─ products/product.html    # detail template (sample)
   │  ├─ manufacturing.html
   │  ├─ quality.html
   │  ├─ gallery.html
   │  ├─ contact.html
   │  ├─ request-quote.html
   │  ├─ styleguide.html          # dev-only, not linked
   │  ├─ partials/
   │  │  ├─ head-meta.html
   │  │  ├─ header.html
   │  │  ├─ footer.html
   │  │  ├─ cta-band.html
   │  │  └─ whatsapp-float.html
   │  ├─ css/app.css              # @import tailwind, @theme tokens, base, components
   │  └─ js/
   │     ├─ main.js               # boots modules
   │     └─ modules/
   │        ├─ header.js          # transparent→solid on scroll, hide on scroll-down
   │        ├─ mobile-nav.js      # focus trap, Esc to close, body scroll lock
   │        ├─ reveal.js          # IntersectionObserver fade/slide, respects reduced motion
   │        ├─ gallery.js         # filters + lightbox (keyboard + swipe)
   │        ├─ product-filter.js  # collection page filters
   │        ├─ forms.js           # validation, quote prefill from ?category=&product=
   │        └─ icons.js           # Lucide createIcons
   └─ public/
      ├─ images/placeholders/{hero,lowers,jackets,manufacturing,quality,details}/
      ├─ images/placeholders/CREDITS.md   # source + licence of every placeholder
      ├─ images/brand/ (logo.svg, og-image.jpg)
      └─ favicon.svg
```

### Phase 2 (Laravel) — key locations
```
app/Http/Controllers/Site/{Home,Page,Product,Gallery,Inquiry}Controller.php
app/Http/Controllers/Admin/{Dashboard,Category,Product,ProductImage,Gallery,Inquiry,Setting,PageContent,Auth}Controller.php
app/Http/Requests/{StoreInquiryRequest, Admin/*Request}.php
app/Models/{Category,Product,ProductImage,GalleryItem,Inquiry,Setting,PageContent,User}.php
app/Services/{ImageService, SettingsService}.php
app/Support/helpers.php              # setting('contact.phone'), content('home.hero.title')
resources/css/app.css                # ← copied verbatim from static-site
resources/js/                        # ← copied verbatim from static-site
resources/views/components/          # x-layouts.app, x-button, x-eyebrow, x-section-heading,
                                     # x-product-card, x-page-hero, x-cta-band, x-image
resources/views/partials/{header,footer}.blade.php
resources/views/pages/*.blade.php
resources/views/admin/**
```

---

## 5. Design system

### 5.1 Colour tokens (exact palette)
```css
@theme {
  --color-ink:        #080808;  /* Primary Black   */
  --color-ink-2:      #171717;  /* Secondary Black */
  --color-graphite:   #252525;  /* Dark Grey       */
  --color-bone:       #F5F5F2;  /* Off White       */
  --color-white:      #FFFFFF;  /* Pure White      */
  --color-champagne:  #A88B60;  /* Accent          */
  --color-mute:       #777777;  /* Secondary Text  */
  --color-line:       #E5E5E5;  /* Border          */
}
```
Distribution: ~65% ink/ink-2/graphite sections, ~30% bone/white sections, ≤5% champagne (eyebrows, thin rules, active states, focus ring, small numerals).

Derived (opacity of palette colours only — no new hues):
- `bone/70` — body text on dark backgrounds
- `bone/12` — thin borders on dark backgrounds (the light `#E5E5E5` is too bright on black)
- `ink/8` — hover fills on light sections

#### ⚠ Accessibility finding on the palette
| Pair | Contrast | WCAG |
|---|---|---|
| `#777777` on `#080808` | 4.47 : 1 | Fails AA for normal text (needs 4.5) — passes for large text |
| `#777777` on `#FFFFFF` | 4.48 : 1 | Same |
| `#777777` on `#F5F5F2` | ~4.1 : 1 | Same |
| `#A88B60` on `#080808` | 6.2 : 1 | Passes AA |
| `#A88B60` on `#FFFFFF` | 3.2 : 1 | Large text / decoration only |
| `#F5F5F2` on `#080808` | 18.6 : 1 | Passes AAA |

**Rule proposed:** `#777777` is used only for text ≥ 18px or ≥ 14px bold (meta, captions in large size, labels). Normal-size secondary text uses `bone/70` on dark and `#252525` on light. Champagne text appears on dark backgrounds only, or as non-text decoration on light ones.

### 5.2 Typography
| Token | Font | Size (fluid) | Line-height | Tracking | Use |
|---|---|---|---|---|---|
| `display` | Cormorant Garamond 500 | `clamp(3rem, 7.5vw, 8rem)` | 0.95 | -0.02em | Homepage hero |
| `h1` | Cormorant 500 | `clamp(2.75rem, 5vw, 5.25rem)` | 1.0 | -0.015em | Page heroes |
| `h2` | Cormorant 500 | `clamp(2.125rem, 3.6vw, 3.75rem)` | 1.08 | -0.01em | Section titles |
| `h3` | Cormorant 500 | `clamp(1.5rem, 2vw, 2rem)` | 1.2 | 0 | Card / item titles |
| `quote` | Cormorant 400 italic | `clamp(1.75rem, 3vw, 2.75rem)` | 1.25 | 0 | Editorial statements |
| `eyebrow` | Inter 500 | 12px | 1 | 0.24em, UPPERCASE | Section labels (champagne) |
| `nav` | Inter 500 | 13px | 1 | 0.14em, UPPERCASE | Navigation |
| `button` | Inter 600 | 12–13px | 1 | 0.16em, UPPERCASE | Buttons |
| `body-lg` | Inter 400 | 18px | 1.75 | 0 | Intros, max 60ch |
| `body` | Inter 400 | 16px | 1.7 | 0 | Default, max 68ch |
| `small` | Inter 400 | 14px | 1.6 | 0.01em | Meta, footer |

### 5.3 Layout & spacing
- **Container:** max 1440px; side padding 20 / 32 / 48 / 64px (mobile → xl).
- **Grid:** 12 columns, gap 24px (mobile 16px). Asymmetric splits used deliberately: 7/5, 5/7, 4/8, offset by 1 column.
- **Section rhythm:** vertical padding `clamp(5rem, 10vw, 10rem)`; compact sections `clamp(3.5rem, 6vw, 6rem)`.
- **Breakpoints:** Tailwind defaults (640 / 768 / 1024 / 1280 / 1536). Tested at 360, 390, 768, 1024, 1280, 1440, 1920.
- **Image ratios:** hero 16:9 desktop / 4:5 mobile; product 4:5; detail 1:1; editorial wide 21:9.

### 5.4 Shape, borders, depth
- **Radius: 0** everywhere (buttons, inputs, images). Only exception: none.
- **Borders:** 1px `line` on light, 1px `bone/12` on dark. Hairline champagne rule (40–64px wide) under eyebrows.
- **Shadows:** none. Depth comes from contrast between ink and bone sections, and from image overlays (`ink/40–60` flat overlay, not gradients — a single subtle bottom scrim is allowed only to keep hero text legible).

### 5.5 Components
| Component | Spec |
|---|---|
| **Button – primary** | White bg, ink text, 52px high, px-8, Inter 600 uppercase. Hover: bg bone, arrow icon slides 4px. |
| **Button – secondary** | Transparent, 1px white border, white text. Hover: white bg, ink text (300ms). |
| **Button – on light** | Ink bg, white text / ink-bordered outline variants. |
| **Text link** | Uppercase nav style + 1px underline that grows from left on hover. |
| **Eyebrow** | `— 01 / CAPABILITIES` style: champagne, tracked caps, optional index number. |
| **Section heading** | Eyebrow + h2 + optional body-lg, left-aligned (centred only in CTA band). |
| **Product card** | Borderless; 4:5 image, slow scale 1.00→1.04 on hover (700ms); name (h3), category + style code in small caps; "Enquire" link. No price. |
| **Category tile** | Full-height split image with large serif label and index numeral. |
| **Numbered list (capabilities/process)** | Row layout: big champagne numeral · title · description, separated by 1px lines. |
| **Spec table** | Two-column definition list (Fabric, GSM, Sizes, Colours, MOQ, Customisation) with hairline separators; rows hidden when empty. |
| **Form fields** | Underline-only inputs (1px bottom border), floating labels, champagne focus line, inline error text + `aria-describedby`. |
| **Header** | 80px; transparent over hero, becomes ink-2 + hairline border after 40px scroll; hides on scroll down, shows on scroll up. Logo left, nav centre, CTA right. |
| **Mobile menu** | Full-screen ink overlay, large serif nav items, contact + WhatsApp at bottom, focus-trapped. |
| **Footer** | Ink background, 4 columns (brand + intro / products / quick links / contact), WhatsApp + social row, copyright bar. |
| **WhatsApp float** | Small square button bottom-right (ink, champagne icon), hidden on quote/contact pages. |
| **Lightbox** | Full-screen ink, arrows, Esc/←/→, swipe, caption, counter. |

### 5.6 Motion
- Easing: `cubic-bezier(0.22, 0.61, 0.36, 1)`. Durations: 200ms (UI), 400ms (buttons/links), 700–900ms (image/reveal).
- Scroll reveal: 24px rise + fade, staggered 80ms; runs once.
- Hero: slow 1.06→1.00 image settle on load.
- **All motion disabled under `prefers-reduced-motion: reduce`.**

### 5.7 Iconography
Lucide, 1.25px stroke, 18–20px, used sparingly (arrows, menu, contact, process steps). No filled or coloured icons.

---

## 6. Page blueprints

URLs shown as final Laravel routes (static files mirror them).

### `/` Home
1. **Hero** — full-bleed image, display headline (tagline), short line, `Request a Quote` (primary) + `Explore Products` (secondary).
2. **Statement** — asymmetric 5/7: eyebrow "About", editorial quote, link to About.
3. **Collections** — two large split tiles: 01 Lowers / Track Pants · 02 Jackets.
4. **Capabilities** — numbered rows (editable list; *you confirm which services you actually offer*: e.g. private label, sampling, fabric sourcing, cutting & stitching, finishing, bulk production).
5. **Manufacturing showcase** — wide image + offset text block, link to Manufacturing.
6. **Process** — Inquiry → Sampling → Approval → Production → Quality Check → Dispatch (horizontal on desktop, vertical on mobile).
7. **Who we work with** — brands, wholesalers, retailers, fashion businesses, private label, bulk buyers (typographic list, no fake logos).
8. **Gallery teaser** — 5-image asymmetric mosaic.
9. **CTA band** — "Start your production" + quote / WhatsApp.

*Reserved, hidden until you supply real data:* stats strip, certifications, client logos, testimonials.

### `/about`
Page hero · story (editable, placeholder copy clearly marked) · mission/vision · values (Precision, Integrity, Craft, Partnership) · approach split-image · CTA.

### `/products`
Page hero · two category feature blocks · "Customisation options" grid (fabric, colour, sizing, branding/labels, packaging — editable) · featured products · CTA.

### `/products/lowers` and `/products/jackets`
Category hero · intro · filter bar (sub-type: track pants / joggers / sports / casual; jackets: sports / track / winter / fashion) · product grid · fabric & detail strip · CTA.

### `/products/{category}/{product}` *(recommended addition)*
Gallery (main + thumbs) · name, style code · description · spec table · "Request a Quote for this product" (prefills form) · related products.

### `/manufacturing`
Hero · overview · stages (Design & Sampling → Fabric Inspection → Cutting → Stitching → Finishing → Packing), each with image · capability notes (editable, no invented numbers) · CTA.

### `/quality`
Hero · quality philosophy · checkpoints (incoming fabric, inline, final inspection, measurement, packing) · what we check (stitching, measurements, colour consistency, trims, finishing) · certifications block (hidden unless provided) · CTA.

### `/gallery`
Hero · filter tabs (All / Lowers / Jackets / Manufacturing / Details) · masonry-style grid · lightbox.

### `/contact`
Split: contact details (address, phone, email, WhatsApp, hours, map embed placeholder) | contact form.

### `/request-quote` *(recommended dedicated page; CTA target)*
Fields: name*, company*, email*, phone/WhatsApp*, country, city, buyer type (brand/wholesaler/retailer/private label/other), category*, product (optional), estimated quantity*, target timeline, customisation needs, message, reference file upload (Phase 2, optional). Consent checkbox.

---

## 7. Content & imagery policy
- All company-specific facts live in **one place** — static: `partials` + a `CONTENT-TODO.md` checklist; Laravel: `settings` + `page_contents` tables.
- Placeholder text uses visible brackets in Phase 1 (e.g. `[Company address]`) so nothing fake ships unnoticed.
- Placeholder photos: royalty-free (e.g. Unsplash licence), stored locally, recorded in `CREDITS.md`, flagged `is_placeholder` in Laravel. Captions describe the *subject* ("Garment stitching detail"), never "Our factory".
- No certifications, capacities, years in business, client names or testimonials are written unless you provide them.

---

## 8. Phase 2 — Data model

```
users            id, name, email (unique), password, is_admin, last_login_at, remember_token, timestamps
categories       id, name, slug (unique), tagline, short_description, description,
                 hero_image, sort_order, is_active, meta_title, meta_description, timestamps
subcategories    id, category_id FK, name, slug, sort_order          (track pants, joggers, winter…)
products         id, category_id FK, subcategory_id FK null, name, slug (unique), style_code null,
                 short_description, description, fabric null, gsm null, sizes JSON, colors JSON,
                 moq null, customization JSON, is_featured, is_active, sort_order,
                 meta_title, meta_description, timestamps, soft_deletes
product_images   id, product_id FK, path, alt, sort_order, is_primary, is_placeholder, timestamps
gallery_items    id, title, group (lowers|jackets|manufacturing|details), path, alt,
                 is_placeholder, is_active, sort_order, timestamps
inquiries        id, type (contact|quote), name, company, email, phone, country, city, buyer_type,
                 category_id null, product_id null, quantity, timeline, message, attachment_path null,
                 status (new|read|replied|archived), admin_notes, ip, user_agent, timestamps
settings         id, group, key (unique), value (text), type (text|textarea|url|email|image|bool)
page_contents    id, page, key, value (text/JSON), type — editable headlines, intros, lists
```
All public queries scope `is_active = true`; products resolved by `{category:slug}/{product:slug}` with scoped bindings.

### Routes
```
GET  /                                   home
GET  /about                              about
GET  /products                           products.index
GET  /products/{category:slug}           products.category
GET  /products/{category:slug}/{product:slug}  products.show
GET  /manufacturing | /quality | /gallery | /contact | /request-quote
POST /contact            throttle:5,1    inquiries.store (type=contact)
POST /request-quote      throttle:5,1    inquiries.store (type=quote)
GET  /sitemap.xml  ·  /robots.txt
/admin/*                 auth + admin middleware
```

---

## 9. Phase 3 — Admin panel & security
**Modules:** Dashboard (new inquiries, counts) · Categories & sub-categories · Products (+ multi-image upload, drag reorder, primary image) · Gallery · Inquiries (list, filter, status, notes, CSV export) · Settings (company, contact, social, SEO) · Page content · Admin profile/password.

**Security:**
- Login only (no public registration), `throttle` + lockout, hashed passwords, session regeneration, `admin` middleware/Gate.
- CSRF on all forms; all output escaped with `{{ }}`; long-form text stored as plain text / limited Markdown (no raw HTML) to remove XSS risk.
- Uploads: `image|mimes:jpg,jpeg,png,webp|max:5120`, dimension limits, **re-encoded server-side** (Intervention Image v3 → WebP, EXIF stripped), random filenames, stored on `public` disk under `storage/app/public/{products,gallery,…}`; quote attachments on private disk, downloadable only by admins.
- Public forms: honeypot + time-trap, rate limiting, optional Cloudflare Turnstile.
- Email notification on new inquiry (queued), `.env` secrets only, `APP_DEBUG=false` in production, security headers middleware.

---

## 10. Quality gates (every module)
- **Responsive:** 360 → 1920 widths, no horizontal scroll.
- **Accessibility:** semantic landmarks, one `h1` per page, skip link, visible champagne focus ring, keyboard-operable menu/lightbox/forms, `alt` on every image, axe DevTools zero criticals, target WCAG 2.2 AA.
- **Performance:** Lighthouse ≥ 90 (all categories); WebP + `srcset`/`sizes`, `loading="lazy"` except LCP image (`fetchpriority="high"`), font preconnect.
- **SEO:** unique title/description, canonical, Open Graph/Twitter tags, `Organization` + `Product` JSON-LD (no invented data), sitemap.
- **Phase 2+:** Pest feature tests for routes, inquiry submission, validation, upload rules, admin auth.

---

## 11. Decisions needed before implementation
1. **Tagline:** "Crafted with precision. Built for excellence." (hero) with "Your vision. Our craftsmanship." used in CTA band — OK?
2. **Request a Quote:** dedicated `/request-quote` page (recommended) or a section on Contact?
3. **Product detail pages:** add `/products/{category}/{product}` (recommended for B2B — lets buyers enquire about a specific style)?
4. **Admin panel (Phase 3):** custom Blade admin (recommended, one stack) or Filament (faster, adds Livewire)?
5. **Logo:** do you have a logo file? If not, Phase 1 uses a typographic wordmark — "HUDAYA" in Cormorant, "ENTERPRISES" in tracked Inter.
6. **Capabilities/services:** confirm which you actually offer (private label, sampling, fabric sourcing, printing/embroidery, packaging, export?) so no claim is invented.
7. **Folder layout:** `static-site/` now, Laravel app created alongside in Phase 2 — OK?
