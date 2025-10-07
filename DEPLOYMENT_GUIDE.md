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

DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME=BAC Purchase System

TWILIO_SID=your-twilio-sid
TWILIO_TOKEN=your-twilio-token
TWILIO_FROM=your-twilio-number

BREVO_API_KEY=your-brevo-api-key
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
