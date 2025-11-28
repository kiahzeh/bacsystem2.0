# 🚀 Deployment Guide for BAC Purchase Request System

## Quick Start - Deploy to Railway (Recommended for Students)

### Step 1: Prepare Your Code

1. **Create a GitHub Repository**
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git branch -M main
   git remote add origin https://github.com/yourusername/bac-purchase-system.git
   git push -u origin main
   ```

2. **Create Railway Account**
   - Go to [railway.app](https://railway.app)
   - Sign up with GitHub
   - Click "New Project" → "Deploy from GitHub repo"

### Step 2: Environment Configuration

Create these environment variables in Railway:

```
APP_NAME=BAC Purchase Request System
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.railway.app
APP_KEY=base64:<paste-generated-key>

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/storage/database.sqlite

MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@example.com
MAIL_FROM_NAME=BAC Purchase System
MAIL_USERNAME=<optional>
MAIL_PASSWORD=<optional>

MIGRATE_RETRIES=10
MIGRATE_SLEEP=5
SKIP_AUTO_MIGRATE=false
SEED_ON_DEPLOY=true
SEEDER_CLASS=DatabaseSeeder

BREVO_KEY=<paste-your-brevo-api-key>
TWILIO_ACCOUNT_SID=<paste-your-twilio-sid>
TWILIO_AUTH_TOKEN=<paste-your-twilio-token>
TWILIO_FROM_NUMBER=<paste-your-twilio-number>
```

### Step 3: Deploy

1. Connect your GitHub repository to Railway
2. Railway will automatically detect it's a Laravel app
3. Add the environment variables above
4. Deploy!

---

## Alternative: Deploy to Vercel (Free)

### Step 1: Install Vercel CLI
```bash
npm i -g vercel
```

### Step 2: Create vercel.json
```json
{
  "version": 2,
  "builds": [
    {
      "src": "public/index.php",
      "use": "@vercel/php"
    }
  ],
  "routes": [
    {
      "src": "/(.*)",
      "dest": "public/index.php"
    }
  ],
  "env": {
    "APP_ENV": "production",
    "APP_DEBUG": "false"
  }
}
```

### Step 3: Deploy
```bash
vercel --prod
```

---

## Alternative: Deploy to DigitalOcean App Platform

### Step 1: Create App Spec
Create `.do/app.yaml`:
```yaml
name: bac-purchase-system
services:
- name: web
  source_dir: /
  github:
    repo: yourusername/bac-purchase-system
    branch: main
  run_command: |
    composer install --no-dev --optimize-autoloader
    php artisan key:generate
    php artisan migrate --force
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan serve --host=0.0.0.0 --port=8080
  environment_slug: php
  instance_count: 1
  instance_size_slug: basic-xxs
  http_port: 8080
  envs:
  - key: APP_ENV
    value: production
  - key: APP_DEBUG
    value: "false"
  - key: DB_CONNECTION
    value: sqlite
  - key: DB_DATABASE
    value: /workspace/database/database.sqlite
databases:
- name: db
  engine: MYSQL
  version: "8"
```

---

## Pre-Deployment Checklist

### ✅ Code Preparation
- [ ] Remove any sensitive data from code
- [ ] Ensure all dependencies are in composer.json
- [ ] Test the application locally
- [ ] Create a .gitignore file

### ✅ Database Setup
- [ ] Run migrations: `php artisan migrate`
- [ ] Seed initial data: `php artisan db:seed`
- [ ] Test database connections

### ✅ File Permissions
- [ ] Ensure storage/ and bootstrap/cache/ are writable
- [ ] Set proper permissions for uploaded files

### ✅ Environment Variables
- [ ] Generate APP_KEY: `php artisan key:generate`
- [ ] Configure database connection
- [ ] Set up email configuration
- [ ] Configure SMS settings (if using)

---

## Post-Deployment Steps

1. **Run Migrations**
   ```bash
   php artisan migrate --force
   ```

2. **Seed Database**
   ```bash
   php artisan db:seed
   ```

3. **Clear Caches**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

4. **Set File Permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

5. **Optional: Use Deploy Script Locally/On Server**
```bash
bash scripts/deploy.sh
```
   This installs prod dependencies, runs migrations, builds assets, and caches config/routes/views.

### Seeding on Deploy

- Enable seeding by setting `SEED_ON_DEPLOY=true` in your environment.
- To run a specific seeder, set `SEEDER_CLASS=YourSeederClass`.
- Behavior:
  - When `SEEDER_CLASS` is provided, the script runs `migrate --force` then `db:seed --class=SEEDER_CLASS --force`.
  - Otherwise, it runs `migrate --force --seed` which triggers `DatabaseSeeder`.

---

## Troubleshooting

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

## Cost Comparison

| Platform | Free Tier | Paid Plans | Best For |
|----------|-----------|------------|----------|
| Railway | ✅ | $5+/month | Students |
| Vercel | ✅ | $20+/month | Static sites |
| DigitalOcean | ❌ | $5+/month | Production |
| Heroku | ❌ | $7+/month | Easy deployment |

---

## Need Help?

If you encounter any issues:
1. Check the logs in your hosting platform
2. Verify all environment variables are set
3. Ensure database migrations ran successfully
4. Check file permissions

Good luck with your deployment! 🎉

---

## Render Post-Deploy (Recommended Settings)

This project includes a Docker‑based Render blueprint (`render.yaml`) tailored for the free tier and PostgreSQL via `DATABASE_URL`.

### What’s configured
- Runtime: Docker (`Dockerfile`) serves Laravel through Apache, builds Vite assets in a separate stage, and runs `docker/start.sh`.
- Health check path: `/robots.txt` (static, fast, doesn’t require app boot).
- Database: `DB_CONNECTION=pgsql` and `DATABASE_URL` for a single connection string; `DB_SSLMODE=require` for secure managed Postgres.
- Laravel runtime: `FILESYSTEM_DISK=public`, `SESSION_DRIVER=file`, `CACHE_DRIVER=file`, `QUEUE_CONNECTION=sync`.
- Startup: storage symlink, config cache, and automatic migrations (`php artisan migrate --force`).

### Deploy to Render (Free Tier)

1) Prerequisites
- Render account
- GitHub repository connected to Render (Blueprint deploy)
- Free PostgreSQL (Neon recommended) for a persistent database

2) Create a Neon database (recommended)
- Sign up at https://neon.tech and create a project
- Copy the connection string (format):
  `postgresql://USER:PASSWORD@HOST:PORT/DBNAME?sslmode=require`

3) Configure environment variables on the Render web service
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
