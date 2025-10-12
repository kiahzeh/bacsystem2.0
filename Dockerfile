FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    sqlite3 \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Create necessary directories
RUN mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache database

# Create .env file
RUN cat > .env << 'EOF'
APP_NAME="BAC Purchase Request System"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=false
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

MAIL_MAILER=log
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=hello@example.com
MAIL_FROM_NAME="BAC Purchase System"
EOF

# Create database file
RUN touch database/database.sqlite && chmod 666 database/database.sqlite

# Generate APP_KEY
RUN php artisan key:generate --force

# Run migrations and seed
RUN php artisan migrate --force && php artisan db:seed --force

# Create storage link
RUN php artisan storage:link || true

# Clear and cache config
RUN php artisan config:clear && php artisan config:cache
RUN php artisan route:clear && php artisan route:cache
RUN php artisan view:clear && php artisan view:cache

# Expose port
EXPOSE 8000

# Start command
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
