#!/bin/bash

echo "🚀 Preparing BAC Purchase Request System for Deployment..."

# Check if .env exists
if [ ! -f .env ]; then
    echo "❌ .env file not found. Please create one from .env.example"
    exit 1
fi

# Generate application key if not set
echo "🔑 Generating application key..."
php artisan key:generate

# Install dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Clear and cache configurations
echo "🧹 Clearing and caching configurations..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Seed database
echo "🌱 Seeding database..."
php artisan db:seed

# Cache configurations for production
echo "⚡ Caching configurations for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "🔐 Setting file permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 644 database/database.sqlite

echo "✅ Deployment preparation complete!"
echo ""
echo "📋 Next steps:"
echo "1. Push your code to GitHub"
echo "2. Choose a hosting platform (Railway, Vercel, or DigitalOcean)"
echo "3. Follow the deployment guide in DEPLOYMENT_GUIDE.md"
echo ""
echo "🌐 Your app is ready to go live!"
