# Hudaya Enterprises — website & admin (Laravel 12)

Public B2B website for a lowers / track pants / jackets manufacturer, with an admin panel for
products, categories, images, gallery, inquiries and company details.

| | |
|---|---|
| Stack | Laravel 12 · PHP 8.2+ · MySQL 8 · Blade · Tailwind CSS v4 · Vite · vanilla JS · Lucide |
| Public site | `/` `/about` `/products` `/products/{category}` `/manufacturing` `/quality` `/gallery` `/contact` |
| Admin | `/admin` (login only — accounts are created with `php artisan admin:create`) |
| Design reference | `../static-site` (approved Phase 1 build) |

## Local development

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate     # then set DB_* in .env
php artisan migrate --seed                           # tables + the approved content
php artisan storage:link
php artisan admin:create                             # your admin login
npm run dev        # Vite (or: npm run build)
php artisan serve  # http://127.0.0.1:8000
```

`php artisan test` runs the test suite (SQLite in memory — no MySQL needed for tests).

## Production

Read **[docs/DEPLOYMENT.md](docs/DEPLOYMENT.md)** — shared hosting and VPS, SSL, permissions, backups.
After deploying, `php artisan hudaya:check` checks the installation and `php artisan hudaya:mail-test you@domain`
checks email.

Audit results: **[docs/PRODUCTION-AUDIT.md](docs/PRODUCTION-AUDIT.md)**.
Photo sources and licences: **[docs/PHOTO-CREDITS.md](docs/PHOTO-CREDITS.md)**.

## Where things are

| Path | What |
|---|---|
| `app/Http/Controllers/Site` | Public pages, quote form (`InquiryController`), sitemap / robots |
| `app/Http/Controllers/Admin` | Admin modules |
| `app/Models`, `app/Enums` | Data model and fixed option lists |
| `app/Services` | `SettingsService` (cached company details), `ImageService` (upload re-encoding) |
| `resources/views/pages`, `components` | Public Blade views (`category.blade.php` renders every category) |
| `resources/views/admin`, `components/admin` | Admin views |
| `resources/css/app.css`, `admin.css` | Public / admin styles (separate builds) |
| `database/seeders` | The approved content — safe to re-run, never overwrites edits |
| `deploy/` | Nginx example, deploy + backup scripts, shared-hosting `index.php` |

## Content rules

Company facts (phone, email, WhatsApp, address, certifications…) stay empty until real values are
entered in **Admin → Company Settings** — empty values are hidden or shown as placeholders, never
invented. The bundled photos are stock images; the site says so (`photo_notice` setting) until they
are replaced with the company's own photography.
