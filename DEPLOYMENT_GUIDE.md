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

DB_CONNECTION=pgsql
DATABASE_URL=<paste-from-railway-postgres-plugin>
DB_SSLMODE=require

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
RUN_DB_SEED=false
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

This project includes a Render configuration (`render.yaml`) tuned for reliable health checks and startup behavior.

### What’s configured
- Health check path: `/robots.txt` (static, fast, doesn’t require app boot). The `/health` route is also available and returns `{"status":"ok"}`.
- Start command: `php -S 0.0.0.0:$PORT -t public public/index.php` with startup prep (cache clears, storage symlink).
- Database migrations: run on startup with retry logic controlled by env vars.
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
