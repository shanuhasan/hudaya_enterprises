# Production readiness audit — 11 November 2026

**Scope:** the complete Laravel 12 application (public site + admin panel).
**Where it was tested:** locally on Windows (XAMPP PHP 8.2.12, MySQL 8.2) in production mode —
`APP_ENV=production`, `APP_DEBUG=false`, config/routes/views cached, OPcache on.
**Not yet tested:** a real server, the live domain, SSL, Apache/Nginx rewrite rules, SMTP email
delivery. The site has **not been deployed**; see "Still to verify on the server".

## Results

| Area | How it was checked | Result |
|---|---|---|
| Public pages (9 + 404) | Real browser (Edge/Chromium), production mode | All load from the database; 0 JS errors; 0 failed requests |
| Admin pages (16) | Real browser, signed in | All load; 0 JS errors |
| Links | Every internal link + in-page anchor on 26 pages (764 links, 108 unique URLs) | 0 broken |
| Database relationships | Feature tests: category → types/products, product ↔ types (pivot), product → images, cascades, restrict-on-delete | Pass |
| CRUD | Feature tests + browser run: products, product images, categories, types, gallery, inquiries, settings, profile | All create / read / update / delete actions work; 0 dead buttons |
| Forms & validation | Server rules for every form; quote form also checked with JavaScript **off** | Errors shown per field, input kept, nothing saved when invalid |
| Image uploads | Real uploads (JPG/PNG/WebP), wrong types (PDF, PHP), too small, too large | Valid images re-encoded (EXIF removed) + 800 px copy; others rejected; files deleted with their record; bundled photos never deleted |
| Quote form → admin | Browser: submit → thank-you → visible in Admin → Inquiries | Works; email notification tested with a fake mailer, failure-safe |
| WhatsApp links | Tests with/without a number | Hidden while empty; `https://wa.me/<number>?text=<encoded message>` when set; contact page has no duplicate floating button |
| Authentication | Tests + browser | Login, logout, wrong password, non-admin rejected, 5-attempt lockout, session regenerated |
| Authorization | Every admin URL and write action as guest and as non-admin | Redirected / signed out; nothing changed |
| CSRF | Real HTTP POST without token to `/contact` and `/admin/login` | 419 |
| Security headers | Response headers | X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy, COOP on all pages; HSTS on HTTPS in production; admin `no-store` + `noindex` |
| Error output | Production mode, broken URLs | Branded 404/419/429/500/503; no stack traces or SQL in responses |
| Mobile / responsive | 26 pages × 7 widths (320–1920 px) | 0 horizontal overflow |
| Accessibility | axe-core (WCAG 2.2 AA + best practice), 26 pages | 0 violations |
| HTML validity | html-validate on server output, 26 pages | 0 errors (one framework notice: Laravel's CSRF field has `autocomplete="off"`) |
| SEO | Titles, descriptions, one `<h1>`, canonical, Open Graph image, JSON-LD, sitemap.xml, robots.txt, 301s from old `.html` URLs | All present; 0 duplicate titles; robots blocks `/admin` in production and everything on non-production copies |
| Visual design | Pixel comparison against the approved static build, 1440 + 390 px | 7 pages identical; Home + Products differ only in featured product names (see below) |
| Page speed | Lighthouse (mobile, simulated), production mode | Accessibility 100 · Best Practices 100 · SEO 100 · Performance 81–97 · CLS 0 · TBT ≤ 30 ms. Two of the nine runs (About, Quality) returned no performance data, so Performance covers only the seven completed pages. |
| Server render time | In-process timing with OPcache | 40–100 ms per page (10–15 ms SQL). The local PHP dev server adds ~450 ms per request, which is why Lighthouse Performance is lower than it should be on a real server. |
| Database performance | Query count per page (automated test with budget) | Public pages 2–6 queries, admin 2–14, independent of data size; lazy loading (N+1) blocked in tests; indexes on all filter/sort columns |
| Dependencies | `composer audit`, `npm audit` | 0 known vulnerabilities |
| Backups | `deploy/backup.sh` run locally, dump restored into a fresh database | Row counts identical; uploads archive complete |
| Automated tests | `php artisan test` | 101 tests, 563 assertions, all passing |

## Fixed during this audit

- Public pages now read from the database: page titles/descriptions/heroes, categories, types, products,
  featured products, gallery, Home mosaic, footer category links (previously fixed content).
- The quote form now posts to Laravel: validation, honeypot, rate limit (3/min, 20/day per IP), saved to
  Admin → Inquiries, email to the company address when set. The old email/WhatsApp hand-off was removed.
- Added `sitemap.xml`, dynamic `robots.txt`, 301 redirects from the Phase 1 `.html` URLs.
- Inactive categories now return 404 and disappear from navigation.
- Security headers middleware; admin pages not cached; branded error pages; HTTPS forcing and proxy support.
- `.htaccess`: blocks hidden files and scripts in `storage/`, compression, long cache for hashed build files.
- Two internal documents (`SHOT-LIST.md`, `CREDITS.md`) were publicly downloadable from `public/images` —
  moved to `docs/`.
- Admin accessibility: low-contrast champagne text on light backgrounds, missing landmarks on the login page,
  unnamed navigation, form controls without types, empty image `src`; 4 px horizontal scroll at 320 px.
- Upload limit lowered from 8000 to 6000 px per side so GD stays within a typical 256 MB memory limit.

## Decisions taken (you did not answer these — reversible)

1. **Featured product names.** The Home/Products "Featured styles" now use the real product records, so the
   cards show "Men's Slim Track Pant" instead of "Classic Track Pant" (6 names change). Rename the products in
   Admin → Products if you prefer the old names.
2. **Inquiry emails** go to the **company email** in Admin → Company Settings. While it is empty, inquiries are
   only saved in the admin (nothing is lost).

## Known limitations

- Product **description, material, sizes, MOQ and extra images** are managed in the admin but are not shown on
  the public site — the approved design has no product detail page. A detail page needs a design decision.
- The bundled photos are stock images (credited in `docs/PHOTO-CREDITS.md`); the site states this until they
  are replaced. The Contact page location image is still a placeholder.

## Still to verify on the server (cannot be tested locally)

- Apache `.htaccess` rules (shared hosting) or the Nginx config (VPS) — the local Apache serves another project,
  so `.htaccess` was not exercised.
- HTTPS certificate, redirects and HSTS on the live domain.
- SMTP: run `php artisan hudaya:mail-test` and confirm the email arrives.
- Real response times on the host (expected well under the 0.5 s seen on the local dev server).
- Follow **docs/DEPLOYMENT.md §C** after deploying; only then is the deployment confirmed.

## Before launch — content you need to supply

Admin → Company Settings: phone, email, WhatsApp number, address, business hours, social links,
certifications (only if real). Replace stock photos with your own and then clear the stock-photo notice.
About page company story, manufacturing figures and the draft copy listed in the Phase 1 report.
