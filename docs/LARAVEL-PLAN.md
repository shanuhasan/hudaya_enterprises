# Hudaya Enterprises — Phase 2: Laravel 12 conversion plan

Supersedes §4 (Phase 2 locations) and §8 (data model) of `ARCHITECTURE.md`, adjusted to the
frontend that was actually approved in Phase 1.

## 1. Ground rules
- **No redesign.** `static-site/` is the visual reference. CSS and JS move across unchanged;
  markup becomes Blade with the same classes and structure. Every module ends with a
  screenshot comparison against the static build (same widths: 390 / 768 / 1440).
- **Nothing invented.** Company facts stay empty until supplied; empty values hide the
  element exactly as they do now (WhatsApp buttons, social icons, certifications…).
- **Stock photos stay labelled.** `photo_notice` setting shown in footer + gallery.
- Laravel 12 conventions: `bootstrap/app.php` for middleware/routing, anonymous Blade
  components, Form Requests, Eloquent route-model binding, Pest tests.

## 2. Stack & location
| | |
|---|---|
| App folder | `laravel/` (next to `static-site/`, which stays as reference) |
| PHP / DB | PHP 8.2 (XAMPP) · MySQL (XAMPP), database `hudaya` |
| Frontend | Blade · Tailwind CSS v4 (`@tailwindcss/vite`) · Vite (`laravel-vite-plugin`) · vanilla JS · Lucide · @fontsource |
| Tests | Pest (feature tests) + the Phase 1 audit scripts (links, axe, overflow, Lighthouse) |

## 3. Folder architecture (MVC)
```
laravel/
├─ app/
│  ├─ Http/
│  │  ├─ Controllers/Site/
│  │  │  ├─ HomeController.php          home: categories, featured products, gallery mosaic
│  │  │  ├─ PageController.php          about, manufacturing, quality (content pages)
│  │  │  ├─ ProductController.php       products index + category pages
│  │  │  ├─ GalleryController.php       gallery page
│  │  │  ├─ ContactController.php       contact page (form + details)
│  │  │  ├─ InquiryController.php       POST inquiry → store, notify, redirect with success
│  │  │  └─ SeoController.php           sitemap.xml, robots.txt
│  │  ├─ Requests/StoreInquiryRequest.php
│  │  └─ Middleware/LegacyHtmlRedirect.php   /lowers.html → /products/lowers (301)
│  ├─ Models/ Setting, Page, Category, ProductType, Product, GalleryItem, Inquiry, User
│  ├─ Enums/  InquiryStatus, BuyerType, GalleryGroup, Timeline
│  ├─ Services/SettingsService.php      cached key→value map, cache busted on save
│  ├─ View/Composers/SiteComposer.php   shares $site to every view
│  └─ Notifications/NewInquiry.php      mail to the company email (queued; log mailer in dev)
├─ database/
│  ├─ migrations/                       one per table (below)
│  └─ seeders/                          Settings, Pages, Catalogue (categories/types/products), Gallery
├─ resources/
│  ├─ css/                              ← static-site/src/css (verbatim)
│  ├─ js/                               ← static-site/src/js (forms.js: real POST instead of mailto)
│  └─ views/
│     ├─ components/
│     │  ├─ layouts/app.blade.php       <x-layouts.app :page="$page"> — head, skip link, chrome
│     │  ├─ header · mobile-menu · footer · logo · floating-actions · cta-band
│     │  ├─ page-hero · breadcrumb · media · product-card · type-card · gallery-item
│     │  └─ lightbox · collection-search · collection-empty · contact-lines · process-steps · stage
│     ├─ pages/  home · about · products · category · manufacturing · quality · gallery · contact
│     └─ seo/sitemap.blade.php
├─ public/images/{brand,placeholders}   ← static-site/public/images (stock photos + @800 copies)
└─ routes/web.php
```
Uploaded images (Phase 3 admin) go to `storage/app/public/…`; the `media` component accepts
either kind of path, so seeded stock photos and uploads work the same way.

## 4. Routes
| Method | URI | Name | Controller |
|---|---|---|---|
| GET | `/` | `home` | HomeController |
| GET | `/about` | `about` | PageController@about |
| GET | `/products` | `products.index` | ProductController@index |
| GET | `/products/{category:slug}` | `products.category` | ProductController@category |
| GET | `/manufacturing` | `manufacturing` | PageController@manufacturing |
| GET | `/quality` | `quality` | PageController@quality |
| GET | `/gallery` | `gallery` | GalleryController |
| GET | `/contact` | `contact` | ContactController (quote form at `#quote`, prefill `?product=&category=`) |
| POST | `/contact` | `inquiries.store` | InquiryController@store — `throttle:5,1`, honeypot, CSRF |
| GET | `/sitemap.xml` | `sitemap` | SeoController@sitemap |
| GET | `/robots.txt` | `robots` | SeoController@robots (static `public/robots.txt` removed) |
| GET | `/{page}.html` | — | 301 to the clean URL (old static links keep working) |

Only active categories resolve (`is_active` scope in route binding → 404 otherwise).
`/styleguide` is not ported (Phase 1 tool only). Product detail pages are **not** added —
they were not in the approved design; can be proposed later.

## 5. Database
```
settings        id · key (unique) · value (text, null) · group · type (text|textarea|email|url|tel)
                · label · help · sort_order · timestamps
                ← replaces site.config.json (name, tagline, phone, phone_href, email, whatsapp_number,
                  whatsapp_message, address, hours, instagram, facebook, linkedin, certifications,
                  photo_notice, announcement, announcement_short, intro …) — 18 keys

pages           id · key (unique: home, about, products, manufacturing, quality, gallery, contact)
                · meta_title · meta_description · hero_eyebrow · hero_title · hero_title_em
                · hero_title_line2 (home headline) · hero_lead · hero_image · hero_image_alt · timestamps
                ← every page's <title>, description and hero become editable

categories      id · name ("Lowers") · slug (unique)
                · tile/showcase: title · title_em · summary · highlights (JSON) · card_image · card_image_alt
                · page: hero_eyebrow · hero_title · hero_title_em · hero_lead · hero_image
                        · intro_title · intro_title_em · intro_text · intro_image · intro_image_alt
                        · intro_image_position (left|right) · types_title · types_title_em
                        · specs (JSON [{label, value}]) · cta_eyebrow · cta_title · cta_title_em · cta_text
                · meta_title · meta_description · sort_order · is_active · timestamps
                ← one Blade template renders both category pages

product_types   id · category_id FK · name ("Men's Lowers") · short_name (tab: "Men's")
                · slug (filter key: mens, joggers, winter…) · description · image · image_alt
                · sort_order · is_active · timestamps · unique(category_id, slug)

products        id · category_id FK · name · slug (unique) · type_label ("Men's · Sports")
                · image · image_alt · featured_position (null = not featured; Home shows 8, Products 4)
                · sort_order · is_active · timestamps · soft deletes

product_product_type   product_id FK · product_type_id FK   (a style can be "mens" AND "sports" — drives the filter tabs)

gallery_items   id · caption · short_caption (Home mosaic label) · group (lowers|jackets|manufacturing|details)
                · image · image_alt · aspect (4/5 | 3/4 | 4/3 | 1/1) · home_position (1–5 = Home mosaic slot)
                · sort_order · is_active · timestamps

inquiries       id · name · company · email · phone · country · city · buyer_type · category
                · product · quantity · timeline · customisation (JSON) · message
                · status (new|read|replied|archived) · admin_notes · source_url · ip · user_agent
                · read_at · timestamps

users           Laravel default (+ is_admin) — used by the Phase 3 admin
```
Seeders load **exactly** the current static content (the same products, types, gallery
items, page heroes and settings — empty values stay empty), so the first Laravel render matches
the approved site 1:1.

Static copy that is not a list (about story, manufacturing stages, quality checkpoints) stays
in Blade for now; the Phase 3 admin can move it to a `page_sections` table if wanted.

## 6. Modules (one at a time, each verified before the next)
| # | Module | Done when |
|---|---|---|
| 1 | **Scaffold + design port**: Laravel 12 app, Vite/Tailwind, CSS/JS/fonts/icons/images, all Blade components, all pages rendered from Blade with static data | All 8 pages screenshot-match the static build; 0 JS errors; links work |
| 2 | **Database**: migrations, models, enums, settings service, seeders | `migrate --seed` on MySQL; models tested |
| 3 | **Pages & SEO**: controllers, dynamic heroes/meta, settings everywhere, sitemap/robots, `.html` redirects | Pages read from DB; still match screenshots |
| 4 | **Products & categories**: products index + category pages from DB, filters/search, product card | Adding a product in DB shows it in the right grid/filter |
| 5 | **Gallery**: gallery page + home mosaic from DB, lightbox | Same |
| 6 | **Inquiries**: Form Request validation, store, email notification, success panel, rate limit, honeypot | Pest tests for valid/invalid/spam submissions |
| 7 | **QA**: full audit (links, axe, overflow, Lighthouse ≥ 90), Pest suite, README | Report |

## 7. What changes in the frontend code (behaviour only, no visual change)
- `forms.js`: the form now **POSTs to Laravel** (client-side validation kept); the
  mailto/WhatsApp hand-off is replaced by the server-side success panel.
- Links: `lowers.html` → `route('products.category', 'lowers')`, etc.
- Image slots: the build-time "skip missing photo / add srcset" logic becomes runtime
  logic in `<x-media>` (same output).
- Tailwind only scans `resources/`. Classes chosen from data (e.g. gallery `aspect-[4/5]`)
  must appear literally in a Blade file (a `match` in the component), never only in PHP
  under `app/` or in the database — otherwise they silently drop out of the CSS.

## 8. Needed from you
- MySQL credentials for XAMPP (root has a password) — needed from Module 2.
- Company email that should receive inquiry notifications (can stay empty: inquiries are
  still stored and visible in the admin).
