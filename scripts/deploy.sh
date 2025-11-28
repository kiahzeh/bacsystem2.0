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

echo "==> Run database migrations"
# Support seeding on deploy via environment flags
# - Set SEED_ON_DEPLOY=true to seed on deploy
# - Optionally set SEEDER_CLASS=YourSeeder to run a specific seeder
if [ "${SEED_ON_DEPLOY:-false}" = "true" ]; then
  echo "==> Migrate with seeding enabled"
  if [ -n "${SEEDER_CLASS:-}" ]; then
    php artisan migrate --force
    php artisan db:seed --class="${SEEDER_CLASS}" --force
  else
    php artisan migrate --force --seed
  fi
else
  php artisan migrate --force
fi

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