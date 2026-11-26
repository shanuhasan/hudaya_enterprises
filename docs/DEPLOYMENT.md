# Hudaya Enterprises — Production deployment guide

Laravel 12 · PHP 8.2+ · MySQL · Vite. Two routes: **A. Shared hosting** (cPanel / hPanel / DirectAdmin)
and **B. VPS** (Ubuntu + Nginx + PHP-FPM). Both end with **C. Verify** — a deployment is only
confirmed once every item in C passes on the live domain.

> **Never expose `.env`.** It holds the database password and the app key. Do not commit it, do not
> email it, do not put it inside `public_html`/`public`. Templates in this repository contain
> placeholders only (`.env.production.example`).

---

## 0. Requirements

| | Minimum | Notes |
|---|---|---|
| PHP | 8.2 (8.3 recommended) | Extensions: `bcmath ctype curl dom fileinfo gd mbstring openssl pdo_mysql tokenizer xml zip` + `exif` (fixes phone-photo rotation). **GD must support JPEG, PNG and WebP.** OPcache on. |
| MySQL | 8.0 (or MariaDB 10.6+) | utf8mb4 |
| Composer | 2.x | on the server (VPS) or locally (shared hosting) |
| Node.js | 20.19+ or 22.12+ | only where the front end is built (locally for shared hosting) |
| HTTPS | certificate for the domain | Let's Encrypt / AutoSSL is enough |

**PHP settings** (`php.ini`, cPanel "MultiPHP INI Editor", or `/etc/php/8.3/fpm/php.ini`):

```ini
upload_max_filesize = 8M      ; one image is max 5 MB
post_max_size = 64M           ; up to 12 images in one upload
max_file_uploads = 20
memory_limit = 256M           ; GD needs ~150 MB for a 6000 × 6000 px photo
max_execution_time = 60
expose_php = Off
opcache.enable = 1
opcache.memory_consumption = 128
opcache.validate_timestamps = 0   ; VPS only — then reload PHP-FPM after each deploy (deploy.sh does it)
```

**What gets deployed:** only the `laravel/` folder. `static-site/` and `docs/` at the repository root
are the Phase 1 reference and are not needed on the server.

---

## A. Shared hosting (cPanel / hPanel / DirectAdmin)

You need **SSH or the hosting "Terminal"** for a few `php artisan` commands. (No terminal at all? See A10.)

### A1. Build the release on your computer

```bash
cd laravel
composer install --no-dev --optimize-autoloader     # production PHP packages only
npm ci
npm run build                                       # creates public/build
```

Make a zip of `laravel/` **without**: `node_modules/`, `tests/`, `.env`, `storage/logs/*.log`,
`storage/framework/{cache,sessions,views}/*` (keep the folders and their `.gitignore` files),
`public/storage` (the link is recreated on the server).

> Afterwards run `composer install` (without `--no-dev`) locally again to get the test tools back.

### A2. Create the database

cPanel → **MySQL® Databases**: create a database (e.g. `acct_hudaya`), a user with a strong
generated password, and add the user to the database with **ALL PRIVILEGES**. Note the three values
for `.env`. Do not use the hosting account's main MySQL login.

### A3. Upload — choose a layout

**Layout 1 (recommended) — the domain points at `laravel/public`.**
Upload the zip and extract it so that the app's files (`artisan`, `app/`, `public/`…) sit directly in
`/home/<account>/hudaya/` — outside `public_html`. In cPanel → **Domains**, set the domain's
*Document Root* to `hudaya/public`. Only that folder is reachable from the web.

**Layout 2 — the document root must stay `public_html`.**
1. Extract the app to `/home/<account>/hudaya/` (outside `public_html`).
2. Move **the contents** of `hudaya/public/` into `public_html/` (including `.htaccess`, `build/`, `images/`, `favicon.png`).
3. Replace `public_html/index.php` with `hudaya/deploy/shared-hosting-index.php` (rename it to `index.php`).
   It loads the app from `../hudaya` and tells Laravel that `public_html` is the public folder.

### A4. Environment file

```bash
cd ~/hudaya
cp .env.production.example .env
nano .env                       # or the File Manager editor — fill in every <…> value
chmod 600 .env
php artisan key:generate --force
```

Keep `APP_URL=http://…` and `SESSION_SECURE_COOKIE=false` until SSL is active (A8), then switch both.

### A5. PHP version and settings

cPanel → **MultiPHP Manager**: PHP 8.2 or 8.3 for the domain. **Select PHP Version → Extensions**:
enable the ones in §0. **MultiPHP INI Editor**: the values in §0 (without the OPcache `validate_timestamps` line).

### A6. Database tables, content, admin account, uploads

```bash
cd ~/hudaya
php artisan migrate --force            # creates the tables
php artisan db:seed --force            # FIRST deployment only: loads the approved content
php artisan admin:create               # asks for email, name and password (min. 10 characters)
php artisan storage:link               # public/storage → storage/app/public (admin uploads)
```

`storage:link` fails ("symlink() has been disabled")? Use a real folder instead:
create `public_html/storage` (Layout 2) or `hudaya/public/storage` (Layout 1), then add to `.env`:
`PUBLIC_STORAGE_ROOT=/home/<account>/public_html/storage` (use your path).

Re-running `db:seed` later is safe (it never overwrites edits), but it is not needed.

### A7. File permissions

```bash
cd ~/hudaya
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;
chmod -R 775 storage bootstrap/cache   # only if 755 is not writable on your host
chmod 600 .env
```
Most shared hosts run PHP as your own user, so 755/644 is enough — `php artisan hudaya:check` tells you.

### A8. SSL (HTTPS)

1. cPanel → **SSL/TLS Status** → run **AutoSSL** (or "Let's Encrypt" in hPanel / DirectAdmin) for the domain and `www`.
2. When the padlock works, open `public/.htaccess` (Layout 2: `public_html/.htaccess`) and **uncomment** the three
   HTTPS lines at the top.
3. In `.env`: `APP_URL=https://your-domain` and `SESSION_SECURE_COOKIE=true`.
4. Behind Cloudflare (orange cloud): set SSL mode **Full (strict)** and add `TRUSTED_PROXIES=*` to `.env`.

### A9. Cache everything

```bash
php artisan optimize          # config, routes, views, events
php artisan hudaya:check      # must end with "No blocking problems found"
```
**After every `.env` change run `php artisan optimize` again** — cached config ignores `.env` edits.

### A10. Without any terminal

Possible but slower: on your computer run `php artisan migrate:fresh --seed` against a local copy,
create the admin with `php artisan admin:create`, export the database with phpMyAdmin/`mysqldump`,
import it on the host with phpMyAdmin, upload the files, generate the key locally with
`php artisan key:generate --show` and paste it into `.env`. You cannot run `optimize`/`storage:link`
— ask the host for terminal access or a one-off cron job (`cd ~/hudaya && php artisan storage:link`).
Change the admin password after the first login.

### A11. Updating later (shared hosting)

1. Locally: pull changes, `composer install --no-dev --optimize-autoloader`, `npm run build`.
2. `php artisan down` on the server.
3. Upload changed files (always the whole `public/build/` folder; never overwrite `.env` or `storage/`).
4. `php artisan migrate --force && php artisan optimize && php artisan up`.

---

## B. VPS (Ubuntu 24.04 · Nginx · PHP-FPM 8.3 · MySQL 8)

Commands assume a sudo user. Replace `example.com`.

### B1. Packages and firewall

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx mysql-server git unzip curl \
  php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip \
  php8.3-gd php8.3-bcmath php8.3-intl php8.3-opcache composer \
  certbot python3-certbot-nginx
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash - && sudo apt install -y nodejs
sudo ufw allow OpenSSH && sudo ufw allow 'Nginx Full' && sudo ufw enable
```
Edit `/etc/php/8.3/fpm/php.ini` with the values from §0, then `sudo systemctl restart php8.3-fpm`.

### B2. MySQL

```bash
sudo mysql_secure_installation
sudo mysql
```
```sql
CREATE DATABASE hudaya CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'hudaya'@'localhost' IDENTIFIED BY '<generate a long random password>';
GRANT ALL PRIVILEGES ON hudaya.* TO 'hudaya'@'localhost';
FLUSH PRIVILEGES;
```
MySQL listens on localhost only by default — keep it that way (no port 3306 in the firewall).

### B3. Code

```bash
sudo adduser --disabled-password deploy
sudo mkdir -p /var/www/hudaya && sudo chown deploy:www-data /var/www/hudaya
sudo -u deploy git clone <repository-url> /var/www/hudaya
```
(Use a read-only deploy key for a private repository.)

### B4. Install

```bash
sudo -iu deploy
cd /var/www/hudaya/laravel
cp .env.production.example .env && nano .env      # fill in every <…>
php artisan key:generate --force
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
npm ci && npm run build
php artisan migrate --force
php artisan db:seed --force          # first deployment only
php artisan storage:link
php artisan admin:create
php artisan optimize
```

### B5. Permissions

```bash
cd /var/www/hudaya/laravel
sudo chown -R deploy:www-data .
sudo find . -type d -exec chmod 755 {} \;
sudo find . -type f -exec chmod 644 {} \;
sudo chmod -R 775 storage bootstrap/cache
sudo find storage bootstrap/cache -type d -exec chmod g+s {} \;   # new files keep the www-data group
sudo chmod 640 .env
```
PHP-FPM (`www-data`) can write only to `storage/` and `bootstrap/cache/`, and read `.env`.

### B6. Nginx

```bash
sudo cp /var/www/hudaya/laravel/deploy/nginx.conf.example /etc/nginx/sites-available/hudaya
sudo nano /etc/nginx/sites-available/hudaya       # domain + PHP-FPM socket
sudo ln -s /etc/nginx/sites-available/hudaya /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t && sudo systemctl reload nginx
```

### B7. SSL

```bash
sudo certbot --nginx -d example.com -d www.example.com --redirect
sudo certbot renew --dry-run      # confirms automatic renewal works
```
Then in `.env`: `APP_URL=https://example.com`, `SESSION_SECURE_COOKIE=true` → `php artisan optimize`.
HSTS is sent by the app automatically on HTTPS in production.

### B8. Backups — see D. Install the cron job now.

### B9. Updating later

```bash
sudo -iu deploy bash /var/www/hudaya/laravel/deploy/deploy.sh
```
Maintenance mode → `git pull` → composer/npm → migrate → caches → reload PHP-FPM → `hudaya:check`.
(Allow the deploy user to reload PHP-FPM without a password: `deploy ALL=NOPASSWD: /bin/systemctl reload php8.3-fpm` via `sudo visudo`.)

---

## C. Verify the live site (both routes) — required before calling it "deployed"

On the server:
```bash
php artisan hudaya:check                       # all PASS (WARN items explained in the output)
php artisan hudaya:mail-test you@your-domain    # the email must arrive
```

In a browser, on the real domain:
- [ ] All pages open: `/`, `/about`, `/products`, `/products/lowers`, `/products/jackets`, `/manufacturing`, `/quality`, `/gallery`, `/contact`; an unknown URL shows the branded 404.
- [ ] Padlock shown; `http://` and `www.` redirect to the main `https://` address.
- [ ] Images and styles load (if unstyled: `public/build` missing; if photos missing: storage link / paths).
- [ ] Send a test inquiry from `/contact` → thank-you message → it appears in **Admin → Inquiries** → the notification email arrives (once the company email is set). Delete the test inquiry.
- [ ] Admin: sign in at `/admin/login`, change the password under **Admin Profile**, upload one test image in **Gallery**, check it on `/gallery`, delete it.
- [ ] **Company Settings**: enter the real phone, email, WhatsApp number, address → WhatsApp buttons appear and open the right chat on a phone.
- [ ] `/robots.txt` shows `Disallow: /admin` and the sitemap line; `/sitemap.xml` lists the pages.
- [ ] `https://your-domain/.env` returns **403/404** (never the file).
- [ ] Security headers: check the domain on securityheaders.com (expect X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy, HSTS).
- [ ] Lighthouse (Chrome DevTools) on Home and one category page from the live domain.
- [ ] Submit `https://your-domain/sitemap.xml` in Google Search Console.

---

## D. Backups and restore

**What to back up:** the MySQL database and `storage/app/public` (uploaded photos). Code and build
files can be rebuilt from the repository; keep a copy of `.env` in a password manager, not with the backups.

**Shared hosting:** enable the host's automatic backups (cPanel → Backup / JetBackup) and, weekly,
download a database export (phpMyAdmin → Export) plus `storage/app/public` (File Manager → Compress).

**VPS:** nightly with `deploy/backup.sh` (database + uploads, 14 days kept, password never on the command line):
```bash
sudo mkdir -p /var/backups/hudaya && sudo chown deploy /var/backups/hudaya
sudo -u deploy crontab -e
15 3 * * * bash /var/www/hudaya/laravel/deploy/backup.sh >> /var/backups/hudaya/backup.log 2>&1
```
Copy the backups off the server as well (e.g. `rclone` to cloud storage) — a backup on the same disk
does not survive a lost server.

**Restore:**
```bash
php artisan down
gunzip -c db_YYYY-MM-DD_HHMM.sql.gz | mysql -u hudaya -p hudaya
tar -xzf uploads_YYYY-MM-DD_HHMM.tar.gz -C /var/www/hudaya/laravel/storage/app
php artisan optimize && php artisan up
```
Test a restore once into a separate database so you know it works before you need it.

---

## E. Security notes

- `APP_DEBUG=false` and `APP_ENV=production` always on the live site (`hudaya:check` fails otherwise).
- Only `public/` is web-accessible. `.htaccess` / Nginx also refuse hidden files and scripts in `storage/`.
- Admin: no public registration; 5 failed logins lock the account+IP for 60 s; admin pages are `noindex`
  and never cached; uploads are re-encoded server-side (EXIF/GPS and hidden content removed).
- Quote form: CSRF token, server validation, honeypot, 3 per minute / 20 per day per IP.
- Keep dependencies patched: `composer audit` and `npm audit` (both reported 0 issues on 2026-11-11).
- Logs: `storage/logs/laravel-YYYY-MM-DD.log` (14 days). They never contain passwords.
- If `.env` is ever exposed: change the database password, run `php artisan key:generate --force`
  (signs everyone out), change the admin password, and the SMTP password.

## F. Troubleshooting

| Symptom | Cause / fix |
|---|---|
| 500 error page | Read `storage/logs/laravel-*.log`. Often: wrong DB values in `.env`, or `storage/` not writable. After fixing `.env`: `php artisan optimize`. |
| Page without styles | `public/build` not uploaded / not built (`npm run build`). |
| Uploaded photos 404 | Storage link missing (`php artisan storage:link`) or `PUBLIC_STORAGE_ROOT` wrong. |
| "Page expired" (419) on forms | Session cookie blocked: `SESSION_SECURE_COOKIE=true` while still on http, or `APP_URL` domain differs from the address used. |
| Links/redirects use http on an https site | Behind a proxy/CDN: set `TRUSTED_PROXIES=*`, then `php artisan optimize`. |
| `.env` changes have no effect | Config is cached — `php artisan optimize`. |
| Image upload fails for large photos | Raise `upload_max_filesize`/`post_max_size`/`memory_limit` (§0); max 5 MB and 6000 px per side. |
