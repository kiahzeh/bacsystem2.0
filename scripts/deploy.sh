#!/usr/bin/env bash
set -euo pipefail

echo "==> Starting deploy"

# Do not create .env during deploy; rely on environment variables

echo "==> Composer install (prod)"
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Ensure SQLite database file exists when using sqlite
if [ "${DB_CONNECTION:-}" = "sqlite" ]; then
  echo "==> Ensure SQLite database file"
  DB_FILE="${DB_DATABASE:-database/database.sqlite}"
  DB_DIR="$(dirname "$DB_FILE")"
  mkdir -p "$DB_DIR" || true
  if [ ! -f "$DB_FILE" ]; then
    echo "Creating $DB_FILE"
    touch "$DB_FILE" || true
  fi
fi

# Respect APP_KEY from environment; only generate if truly missing and .env exists
if [ -z "${APP_KEY:-}" ]; then
  if [ -f .env ] && ( ! grep -q '^APP_KEY=' .env || grep -q '^APP_KEY=$' .env ); then
    echo "==> APP_KEY missing in .env; generating once"
    php artisan key:generate --force
  else
    echo "==> WARNING: APP_KEY not set; please set environment APP_KEY"
  fi
else
  echo "==> APP_KEY provided via environment; skipping generation"
fi

echo "==> Run migrations"
php artisan migrate --force --no-seed

echo "==> Storage link"
php artisan storage:link || true

echo "==> Build assets"
npm ci
npm run build

echo "==> Cache config/routes/views"
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Deploy complete"