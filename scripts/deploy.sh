#!/usr/bin/env bash
set -euo pipefail

echo "==> Starting deploy"

# Ensure env exists (copy template if missing)
if [ ! -f .env ]; then
  echo "==> .env missing; copying .env.production.example"
  cp .env.production.example .env
fi

echo "==> Composer install (prod)"
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

# Generate key if missing
if ! grep -q '^APP_KEY=' .env || grep -q '^APP_KEY=$' .env; then
  echo "==> Generating APP_KEY"
  php artisan key:generate --force
fi

echo "==> Run migrations"
php artisan migrate --force

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