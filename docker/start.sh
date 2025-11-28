#!/usr/bin/env sh
set -euo pipefail

PORT="${PORT:-8080}"

# Ensure Apache listens on the provided PORT
printf "Listen %s\n" "$PORT" > /etc/apache2/ports.conf
printf "ServerName localhost\n" > /etc/apache2/conf-available/servername.conf
 a2enconf servername

# Align the default vhost to the runtime PORT (from 80)
sed -i "s#<VirtualHost \*:[0-9]\+#<VirtualHost *:${PORT}#" /etc/apache2/sites-available/000-default.conf

# Ensure DocumentRoot points to public (patched at build time)
# and required modules are enabled
apache2ctl -M | grep -q rewrite || a2enmod rewrite

# Log what port we’re using
echo "Starting Apache on port $PORT"

# Prepare Laravel cache, logs, and view compiled directories
mkdir -p /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache
# Ensure the log file exists so Monolog can append without permission issues
touch /var/www/html/storage/logs/laravel.log || true
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# If using SQLite, ensure the database file exists and is writable
if [ "${DB_CONNECTION:-}" = "sqlite" ]; then
  DB_FILE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
  DB_DIR="$(dirname "$DB_FILE")"
  mkdir -p "$DB_DIR"
  if [ ! -f "$DB_FILE" ]; then
    touch "$DB_FILE" || true
    echo "Created SQLite database at $DB_FILE"
  fi
  chown -R www-data:www-data "$DB_DIR" || true
  chmod 775 "$DB_DIR" || true
  chmod 664 "$DB_FILE" || true
fi

# Storage symlink
if [ ! -L /var/www/html/public/storage ]; then
  php artisan storage:link || true
fi

# Proactively clear caches to avoid stale compiled views/routes/config
php artisan optimize:clear || true
php artisan view:clear || true
php artisan route:clear || true
php artisan cache:clear || true

# Refresh config and run migrations if not skipped
php artisan config:clear || true
php artisan config:cache || true

# Log DB target to aid debugging
echo "DB_CONNECTION=${DB_CONNECTION:-}"
if [ -n "${DATABASE_URL:-}" ]; then
  echo "DATABASE_URL is set"
else
  echo "DATABASE_URL is NOT set"
fi

if [ "${SKIP_AUTO_MIGRATE:-false}" != "true" ]; then
  RETRIES="${MIGRATE_RETRIES:-1}"
  SLEEP_SECS="${MIGRATE_SLEEP:-5}"
  ATTEMPT=1
  until php artisan migrate --force; do
    echo "Migration attempt ${ATTEMPT}/${RETRIES} failed"
    if [ "$ATTEMPT" -ge "$RETRIES" ]; then
      echo "Migrations failed after ${RETRIES} attempts; continuing startup"
      break
    fi
    ATTEMPT=$((ATTEMPT+1))
    echo "Retrying in ${SLEEP_SECS}s..."
    sleep "$SLEEP_SECS"
  done
  if [ "${RUN_DB_SEED:-false}" = "true" ]; then
    php artisan db:seed --force || true
  fi
fi

exec /usr/local/bin/apache2-foreground