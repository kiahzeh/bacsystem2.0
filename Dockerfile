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
RUN echo 'APP_NAME="BAC Purchase Request System"' > .env && \
    echo 'APP_ENV=production' >> .env && \
    echo 'APP_KEY=' >> .env && \
    echo 'APP_DEBUG=false' >> .env && \
    echo 'APP_URL=https://your-app.up.railway.app' >> .env && \
    echo '' >> .env && \
    echo 'LOG_CHANNEL=stderr' >> .env && \
    echo 'LOG_LEVEL=error' >> .env && \
    echo '' >> .env && \
    echo 'DB_CONNECTION=sqlite' >> .env && \
    echo 'DB_DATABASE=/app/database/database.sqlite' >> .env && \
    echo '' >> .env && \
    echo 'BROADCAST_DRIVER=log' >> .env && \
    echo 'CACHE_DRIVER=file' >> .env && \
    echo 'FILESYSTEM_DISK=local' >> .env && \
    echo 'QUEUE_CONNECTION=sync' >> .env && \
    echo 'SESSION_DRIVER=file' >> .env && \
    echo 'SESSION_LIFETIME=120' >> .env && \
    echo 'SESSION_SECURE_COOKIE=false' >> .env && \
    echo 'SESSION_HTTP_ONLY=true' >> .env && \
    echo 'SESSION_SAME_SITE=lax' >> .env && \
    echo '' >> .env && \
    echo 'MAIL_MAILER=log' >> .env && \
    echo 'MAIL_HOST=mailpit' >> .env && \
    echo 'MAIL_PORT=1025' >> .env && \
    echo 'MAIL_USERNAME=null' >> .env && \
    echo 'MAIL_PASSWORD=null' >> .env && \
    echo 'MAIL_ENCRYPTION=null' >> .env && \
    echo 'MAIL_FROM_ADDRESS=hello@example.com' >> .env && \
    echo 'MAIL_FROM_NAME="BAC Purchase System"' >> .env

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
EXPOSE $PORT

# Start command
CMD php artisan serve --host=0.0.0.0 --port=$PORT
