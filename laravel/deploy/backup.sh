#!/usr/bin/env bash
# =============================================================================
# Nightly backup: MySQL database + uploaded images. See docs/DEPLOYMENT.md, section D.
#   bash /var/www/hudaya/laravel/deploy/backup.sh
# Cron (03:15 every night):
#   15 3 * * * bash /var/www/hudaya/laravel/deploy/backup.sh >> /var/backups/hudaya/backup.log 2>&1
#
# The database password is read from .env and passed to mysqldump through a temporary
# option file readable only by this user — it never appears in the process list or logs.
# =============================================================================
set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/hudaya/laravel}"
BACKUP_DIR="${BACKUP_DIR:-/var/backups/hudaya}"
KEEP_DAYS="${KEEP_DAYS:-14}"
STAMP="$(date +%Y-%m-%d_%H%M)"

umask 077
mkdir -p "$BACKUP_DIR"

env_value() { grep -E "^$1=" "$APP_DIR/.env" | tail -n1 | cut -d= -f2- | sed -e 's/^"//' -e 's/"$//'; }

DB_HOST="$(env_value DB_HOST)"
DB_PORT="$(env_value DB_PORT)"
DB_NAME="$(env_value DB_DATABASE)"
DB_USER="$(env_value DB_USERNAME)"
DB_PASS="$(env_value DB_PASSWORD)"

CNF="$(mktemp)"
trap 'rm -f "$CNF"' EXIT
printf '[client]\nhost=%s\nport=%s\nuser=%s\npassword=%s\n' "$DB_HOST" "${DB_PORT:-3306}" "$DB_USER" "$DB_PASS" > "$CNF"

echo "[$STAMP] database → $BACKUP_DIR/db_$STAMP.sql.gz"
mysqldump --defaults-extra-file="$CNF" --single-transaction --quick --routines --no-tablespaces "$DB_NAME" | gzip > "$BACKUP_DIR/db_$STAMP.sql.gz"

echo "[$STAMP] uploads  → $BACKUP_DIR/uploads_$STAMP.tar.gz"
tar -czf "$BACKUP_DIR/uploads_$STAMP.tar.gz" -C "$APP_DIR/storage/app" public

echo "[$STAMP] removing backups older than $KEEP_DAYS days"
find "$BACKUP_DIR" -type f \( -name 'db_*.sql.gz' -o -name 'uploads_*.tar.gz' \) -mtime +"$KEEP_DAYS" -delete

# Sanity check: a database dump smaller than 2 KB means something went wrong.
SIZE=$(stat -c%s "$BACKUP_DIR/db_$STAMP.sql.gz")
if [ "$SIZE" -lt 2048 ]; then
  echo "[$STAMP] WARNING: database backup is only $SIZE bytes — check it!" >&2
  exit 1
fi
echo "[$STAMP] OK"
