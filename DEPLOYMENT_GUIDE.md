# 🚀 Deployment Guide for BAC Purchase Request System

This guide provides instructions for deploying the BAC Purchase Request System to various cloud platforms.

## Table of Contents

- [General Setup & Pre-Deployment](#-general-setup--pre-deployment)
- [Supported Platforms](#-supported-platforms)
  - [Render (Recommended)](#-deploy-to-render-recommended)
  - [Railway](#-deploy-to-railway)
  - [DigitalOcean App Platform](#-deploy-to-digitalocean-app-platform)
  - [Vercel (Not Recommended for this App)](#-deploy-to-vercel-not-recommended-for-this-app)
- [Post-Deployment Steps](#-post-deployment-steps)
- [Troubleshooting](#-troubleshooting)
- [Cost Comparison](#-cost-comparison)

---

## ✅ General Setup & Pre-Deployment

Before deploying to any platform, ensure you have completed these steps.

### 1. Code Preparation
- [x] **Create a GitHub Repository:** Your code must be in a GitHub repository for these platforms to access it.
- [x] **Remove Sensitive Data:** Never commit secrets like API keys or passwords to your repository. Use environment variables instead.
- [x] **Check `composer.json`:** Ensure all required PHP packages are listed.
- [x] **Create a `.gitignore` file:** Use the standard Laravel `.gitignore` to exclude `vendor`, `.env`, `storage/logs`, etc.

### 2. Generate `APP_KEY`
Your application will fail with a 500 error without a valid `APP_KEY`. Generate one locally and copy it to your hosting provider's environment variables.

```bash
# Run this in your local project terminal
php artisan key:generate --show
```

Copy the output (e.g., `base64:xxxxxxxx...`) and set it as the `APP_KEY` environment variable on your chosen platform.

### 3. Environment Variables
All platforms require you to set environment variables. Here are the most common ones. Refer to the platform-specific sections for database details.

```ini
# Application
APP_NAME="BAC Purchase Request System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://<your-app-url>
APP_KEY= # Paste the key you generated here

# Logging
LOG_CHANNEL=stderr

# Mail (using Brevo/Sendinblue as an example)
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=<brevo-smtp-login>
MAIL_PASSWORD=<brevo-smtp-password>
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🏁 Post-Deployment Steps

1. **Run Migrations**
   ```bash
   php artisan migrate --force
   ```

2. **Seed Database**
   ```bash
   # It's safer to run seeders manually after the first deploy
   php artisan db:seed --class=YourSeederClass --force
   ```

3. **Clear Caches**
   ```bash
   # The start.sh script handles this, but you can run it manually if needed
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. **Optional: Use Deploy Script Locally/On Server**
```bash
bash scripts/deploy.sh
```
   This installs prod dependencies, runs migrations, builds assets, and caches config/routes/views.

### Seeding on Deploy
**Warning:** Automatic seeding on every deploy (`RUN_DB_SEED=true`) is risky in production as it may duplicate or overwrite data. It's recommended to run seeds manually.

- To enable, set `RUN_DB_SEED=true`.
- To run a specific seeder, also set `SEEDER_CLASS=YourSeederClass`.
- If `RUN_DB_SEED` is true and `SEEDER_CLASS` is not set, it will run `DatabaseSeeder`.

---

## ❓ Troubleshooting

### Common Issues:

1. **500 Error**: Check APP_KEY is set
2. **Database Error**: Ensure database file exists and is writable
3. **File Upload Issues**: Check storage permissions
4. **Email Not Working**: Verify SMTP settings

### Debug Commands:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## 📦 Supported Platforms

### ⭐ Deploy to Render (Recommended)

This project includes a Docker‑based Render blueprint (`render.yaml`) tailored for the free tier and PostgreSQL via `DATABASE_URL`.

### What’s configured
- Runtime: Docker (`Dockerfile`) serves Laravel through Apache, builds Vite assets in a separate stage, and runs `docker/start.sh`.
- Health check path: `/robots.txt` (static, fast, doesn’t require app boot).
- Database: `DB_CONNECTION=pgsql` and `DATABASE_URL` for a single connection string; `DB_SSLMODE=require` for secure managed Postgres.
- Laravel runtime: `FILESYSTEM_DISK=public`, `SESSION_DRIVER=file`, `CACHE_DRIVER=file`, `QUEUE_CONNECTION=sync`.
- Startup: storage symlink, config cache, and automatic migrations (`php artisan migrate --force`).

#### Deploy with PostgreSQL (Free Tier)

1. **Prerequisites**
- Render account
- GitHub repository connected to Render (Blueprint deploy)
- Free PostgreSQL (Neon recommended) for a persistent database

2. **Create a Neon database (recommended)**
- Sign up at https://neon.tech and create a project
- Copy the connection string (format):
  `postgresql://USER:PASSWORD@HOST:PORT/DBNAME?sslmode=require`

3. **Configure environment variables on the Render web service**
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://<your-service>.onrender.com
APP_KEY=base64:<run-locally: php artisan key:generate --show>

DB_CONNECTION=pgsql
DATABASE_URL=postgresql://USER:PASSWORD@HOST:PORT/DBNAME?sslmode=require
DB_SSLMODE=require

FILESYSTEM_DISK=public
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=<your-email>
MAIL_FROM_NAME=BAC System
MAIL_USERNAME=<brevo-smtp-login>
MAIL_PASSWORD=<brevo-smtp-password>

MIGRATE_RETRIES=10
MIGRATE_SLEEP=5
SKIP_AUTO_MIGRATE=false
RUN_DB_SEED=false

BREVO_KEY=<optional-brevo-api-key>
```

4) Deploy
- From Render, click “New +” → “Blueprint” and point to your repo
- The service builds the image, runs Apache on `$PORT`, and applies runtime prep
- First boot runs migrations automatically

5) Verify
- Visit `/` and check pages and assets
- Upload a document; View should open inline in a new tab; Download should work
- Restart service; confirm database data persists (Neon)

### Render with SQLite (Temporary Setup)

Use this for quick demos/defences. It uses a persistent disk so your database survives redeploys.

1) What’s configured (via `render.yaml`)
- A persistent disk mounted at `/var/data`.
- `DB_CONNECTION=sqlite` and `DB_DATABASE=/var/data/database.sqlite` by default.
- Automatic migrations on start with retries.
- Worker service for background jobs (sequential on SQLite).

2) Environment variables (Web + Worker)
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://<your-service>.onrender.com
APP_KEY=base64:<run-locally: php artisan key:generate --show>

FILESYSTEM_DISK=public
SESSION_DRIVER=file
CACHE_DRIVER=file

DB_CONNECTION=sqlite
DB_DATABASE=/var/data/database.sqlite

MIGRATE_RETRIES=10
MIGRATE_SLEEP=5
SKIP_AUTO_MIGRATE=false
RUN_DB_SEED=false

MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=<your-email>
MAIL_FROM_NAME=BAC System
MAIL_USERNAME=<brevo-smtp-login>
MAIL_PASSWORD=<brevo-smtp-password>

BREVO_KEY=<optional-brevo-api-key>
```

3) Tips
- Keep a single web instance; avoid autoscaling on SQLite.
- The Worker runs `queue:work` and processes jobs one-by-one. If you see lock warnings under load, reduce throughput or temporarily set `QUEUE_CONNECTION=sync`.
- For backups, download `/var/data/database.sqlite` periodically or copy to object storage.

4) Upgrade to Postgres later
- Attach a managed Postgres and set `DB_CONNECTION=pgsql` and `DATABASE_URL`.
- Redeploy; migrations run automatically.
- Optional data migration from SQLite: `sqlite3 database.sqlite .dump > dump.sql` → adjust types/indexes → import with `psql`.

### Free Tier Storage Considerations

- Without a persistent disk, files in `storage/app/public` are reset on redeploys.
- Day‑to‑day service restarts typically keep the ephemeral filesystem intact, but builds wipe it.
- If you need permanent uploads on the free tier, use a free S3‑compatible provider (Cloudflare R2) and switch the `public` disk to S3.

Example `.env` for Cloudflare R2:
```
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=<r2-access-key>
AWS_SECRET_ACCESS_KEY=<r2-secret-key>
AWS_DEFAULT_REGION=auto
AWS_BUCKET=<r2-bucket-name>
AWS_URL=https://<account-id>.r2.cloudflarestorage.com/<r2-bucket-name>
AWS_ENDPOINT=https://<account-id>.r2.cloudflarestorage.com
AWS_USE_PATH_STYLE_ENDPOINT=true
```

Then ensure your uploads use the `public` URL from `s3`:
- In `config/filesystems.php`, `s3` disk is already defined; switching `FILESYSTEM_DISK` to `s3` routes uploads there.
- Use `Storage::disk('s3')` for any explicit operations.

### Troubleshooting on Render
- 500 error at boot: check `APP_KEY` and that `config:cache` ran cleanly.
- Database connection fails: verify `DATABASE_URL` and that `sslmode=require` is present (or `DB_SSLMODE=require`).
- Missing assets: ensure the Docker build copies `/public/build` (the `Dockerfile` assets stage does this).
- Storage link missing: the startup script runs `php artisan storage:link`; recheck if you’ve overridden it.
- Optional seeding: disabled by default, can be enabled via env var.

### Required environment variables
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_KEY` (must be set)
- `APP_URL=https://<your-render-app>`
- `DB_CONNECTION=pgsql`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### Startup control variables (set in Render if needed)
- `MIGRATE_RETRIES=10` (number of retry attempts for migrations)
- `MIGRATE_SLEEP=5` (seconds to sleep between attempts)
- `SKIP_AUTO_MIGRATE=false` (set to `true` to skip running migrations on start)
- `RUN_DB_SEED=false` (set to `true` to run `php artisan db:seed --force` on start)

### Generate and set APP_KEY
- Option A (Artisan):
  ```bash
  php artisan key:generate --show
  ```
  Copy the printed key and set it as `APP_KEY` in Render.
- Option B (PHP CLI):
  ```bash
  php -r "echo 'base64:'.base64_encode(random_bytes(32));"
  ```

### Deploy flow on Render
1. Push changes to your repo connected to Render.
2. Ensure env vars above are set, especially `APP_KEY` and DB credentials.
3. Trigger a deploy.
4. Verify health:
   - Open `https://<your-render-app>/health` → should return `{"status":"ok"}`.
   - Open `/` and `/dashboard` (after login) → should load without 500s.

### Common issues and fixes
- 500 error on `/` or `/dashboard`:
  - Check `APP_KEY` is set.
  - Confirm DB env vars are correct and the DB is reachable.
  - Review Render logs for migration failures; adjust `MIGRATE_RETRIES`/`MIGRATE_SLEEP` if needed.
- Migrations failing at build time:
  - In this setup, migrations are run at start, not at build.
  - If you must skip, set `SKIP_AUTO_MIGRATE=true` and run migrations manually.
- Need seed data in production:
  - Temporarily set `RUN_DB_SEED=true` for one deploy, then set it back to `false`.

### Notes
- `render.yaml` omits migrations and seeding from `buildCommand` to avoid DB availability issues in build phase.
- The `/health` route is defined in `routes/web.php` and returns HTTP 200.


## Railway Post-Deploy (Server-First Health Checks)

This project includes Railway configuration (`railway.json` + `nixpacks.toml`) tuned for fast boot and reliable health checks.

### What’s configured
- Health check path: `/robots.txt` (static, quick). The `/health` route returns `{"status":"ok"}`.
- Start order: the PHP server starts immediately; cache/storage prep and migrations run afterward. Migrations use a retry loop and the process waits on the server.
- Builder: Nixpacks; `.railwayignore` prevents `Dockerfile` from overriding Nixpacks.

### Verify after deploy
- Open `https://<your-app>.railway.app/robots.txt` → should return 200 within seconds.
- Open `https://<your-app>.railway.app/health` → should return `{"status":"ok"}`.
- Check logs: you should see messages like `Migration attempt X failed; retrying in Y seconds...` while the server stays healthy.

### Tune migration behavior
- `MIGRATE_RETRIES=10` and `MIGRATE_SLEEP=5` to handle Postgres readiness delays.
- `SKIP_AUTO_MIGRATE=true` to skip migrations during deploy; run manually after healthy.
- `RUN_DB_SEED=true` only when you need seed data; set it back to `false` afterward.

### Manual operations (healthy service)
- Migrate: `railway run php artisan migrate --force`
- Seed: `railway run php artisan db:seed --force`
- Env check: `railway run php artisan env:check`

### Database settings (Postgres)
- Use `DATABASE_URL` from the Railway Postgres plugin.
- Set `DB_SSLMODE=require` for public endpoints.

### Avoid common pitfalls
- Ensure `APP_KEY` is set; missing keys cause 500s.
- Don’t override the start command in the Railway UI unless you mirror server-first boot.
- If health fails repeatedly, temporarily set `SKIP_AUTO_MIGRATE=true` and migrate via CLI once healthy.
