#!/usr/bin/env bash
# =============================================================================
# Update deployment on a VPS (git-based). See docs/DEPLOYMENT.md, section B9.
# Run as the deploy user from anywhere:   bash /var/www/hudaya/laravel/deploy/deploy.sh
# Stops on the first error; the site stays in maintenance mode until it succeeds.
# =============================================================================
set -euo pipefail

REPO_DIR="${REPO_DIR:-/var/www/hudaya}"
APP_DIR="$REPO_DIR/laravel"

cd "$APP_DIR"

echo "→ Maintenance mode on"
php artisan down --retry=30 || true

# Leave maintenance mode even if a later step fails, so the site is never stuck "down"
# without anyone noticing — but report the failure.
trap 'echo "✗ Deployment FAILED — check the output above. Bringing the site back up."; php artisan up || true' ERR

echo "→ Pull latest code"
git -C "$REPO_DIR" pull --ff-only

echo "→ PHP dependencies (no dev packages)"
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

echo "→ Front-end build"
npm ci --no-audit --no-fund
npm run build

echo "→ Database migrations"
php artisan migrate --force

echo "→ Caches"
php artisan optimize:clear
php artisan optimize

echo "→ Reload PHP-FPM (clears OPcache)"
sudo systemctl reload php8.3-fpm || echo "  (reload PHP-FPM manually if the service name differs)"

php artisan up
trap - ERR

echo "→ Post-deployment check"
php artisan hudaya:check
echo "✓ Done. Now open the site in a browser and test it (docs/DEPLOYMENT.md §C)."
