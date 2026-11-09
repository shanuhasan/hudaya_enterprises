# Hudaya Enterprises — Static Frontend (Phase 1)

HTML5 · Tailwind CSS v4 · Vanilla JS · Lucide · self-hosted fonts · built with Vite.

## Commands
```bash
npm install        # once (Node 20.19+ or 22.12+)
npm run dev        # live preview → http://localhost:5173 (opens the style guide)
npm run build      # production files → dist/
npm run preview    # serve dist/ locally → http://localhost:4173
npm run shots      # rebuild + regenerate public/images/placeholders/SHOT-LIST.md
```
`dist/` uses relative URLs, so it also works when copied anywhere under XAMPP `htdocs`.

## Company details — `src/site.config.json`
All company facts live in this one file. **Empty string = not set yet.**

| Key | When empty | When set |
|---|---|---|
| `phone`, `phoneHref` | `[Phone number]` shown as plain text | clickable `tel:` link (`phoneHref` in `+91…` format) |
| `email` | `[Email address]` shown as plain text | `mailto:` link, form can send by email |
| `whatsappNumber` | all WhatsApp buttons are hidden | WhatsApp buttons appear site-wide (digits only, with country code, e.g. `9198…`) |
| `address`, `hours` | bracketed placeholder text | shown in footer / contact page / structured data |
| `instagram`, `facebook`, `linkedin` | icon hidden | icon shown in footer |
| `certifications` | Quality page section hidden | section shown |
| `photoNotice` | no notice shown | footer + gallery line saying the photos are stock imagery — **clear it once real photography replaces the stock photos** |
| `siteUrl` | no canonical / og:image / sitemap | adds canonical, og:url, og:image and `sitemap.xml` (no trailing slash, e.g. `https://www.example.com`) |

The build prints which of these are still empty.

## Photos
Every image is a slot with a fixed path (see `public/images/placeholders/SHOT-LIST.md`).
Drop a file at that path and it replaces the current image automatically — no code changes.
Missing photos are left out of the HTML at build time, so there are no 404 requests.

**Current state:** 54 of 55 slots hold **stock photos from Unsplash** (listed with photographers in
`CREDITS.md`), colour-graded to the black/champagne palette. They are not Hudaya Enterprises
photography; `photoNotice` says so on the site. The location slot is left empty on purpose.

**Small copies:** `name@800.jpg` next to `name.jpg` is an 800px copy for phones; the build adds
`srcset` automatically. When you replace a photo, its old small copy becomes older than the photo and
is ignored, so the new photo is always used (optionally create a new 800px copy for speed).

## Logo
Supplied artwork, processed into `public/images/brand/`:
`logo.png` (original colours, transparent), `logo-light.png` (off-white lettering for dark backgrounds),
`logo-lockup-light.png` (horizontal, used in header/drawer/footer via `components/logo.html`),
`logo-mark.png` (H mark), `favicon.png`, `icon-192.png`, `apple-touch-icon.png`, `og-image.png`.
A vector (SVG) version from the designer would be sharper and lighter — swap it into `components/logo.html`.

## Motion rules
- Normal interactions 200–400ms (`--dur-ui` 200, `--dur-base` 300, `--dur-drawer` 380, `--dur-image` 400).
  Only the one-time scroll reveal uses 600ms with 16px of travel.
- Only `transform`, `opacity` and colours animate. Hover zoom applies on hover-capable devices only.
- `prefers-reduced-motion: reduce` disables all transitions, reveals and smooth scrolling (base.css).
- One rAF-throttled scroll listener (`header.js`) and one IntersectionObserver (`reveal.js`) for the whole site.

## Where things live
| Path | Purpose |
|---|---|
| `src/*.html` | Pages (auto-detected by `vite.config.js`) |
| `src/components/` | Shared HTML: head, header, drawer, footer, CTA band, cards, hero, media slot, lightbox… |
| `src/site.config.json` | All company details (see above) |
| `src/css/tokens.css` | Design tokens: colours, fonts, type scale, container, motion |
| `src/css/*.css` | base · layout · components · sections · pages · interactions |
| `src/js/modules/` | One file per behaviour; page-specific modules are lazy-loaded |
| `src/icons/brand/` | Brand SVGs not in Lucide (WhatsApp, Instagram, Facebook, LinkedIn) |
| `public/images/brand/` | Logo files, icons, `og-image.png` (1200×630) |
| `public/images/placeholders/` | Photo slots + `SHOT-LIST.md` + `CREDITS.md` |
| `build/html-include.js` | Build-time templating (includes, variables, conditionals, image slots) |
| `build/seo-files.js` | Writes `robots.txt` and (with `siteUrl`) `sitemap.xml` |
| `build/shot-list.js` | Generates the photography shot list |

## Template syntax (build time, Blade-compatible)
```html
{{-- note, removed from output --}}
<!-- @include components/head.html {"title": "About Us", "description": "..."} -->
{{ site.phone }}   {{ site.phone ?? '[Phone number]' }}   {{ site.whatsappMessage | url }}   {{ site.name | json }}
<!-- @if site.whatsappNumber && !hideWhatsapp --> … <!-- @endif -->
<body data-nav-active="about">          <!-- highlights the About nav link -->
<body data-header="overlay">            <!-- transparent header over a full-bleed hero -->
```

## Surfaces
Every section uses one surface class: `surface-ink`, `surface-ink-2`, `surface-graphite`, `surface-bone`, `surface-white`.
Components adapt automatically. Use `text-fg`, `text-fg-muted`, `text-fg-strong`, `text-accent`, `border-rule`. Avoid raw colours.

## Rules
- Only the 8 palette colours exist (Tailwind defaults are disabled). No radius, no shadows.
- `#777777` (`mute`) only for text ≥ 18px. Champagne text only on dark surfaces.
- Never caption a placeholder photo as company-owned. Never publish invented figures or certifications.
