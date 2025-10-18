# Simple production Dockerfile for Laravel + Apache

# Build static assets with Vite (keeps views from 500s)
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY resources ./resources
COPY public ./public
ENV NODE_ENV=production
RUN npm run build

# Runtime image
FROM php:8.2-apache

# Install PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev libonig-dev libxml2-dev zip unzip libpq-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Add Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# App files
WORKDIR /var/www/html
COPY . .

# Copy built assets
COPY --from=assets /app/public/build /var/www/html/public/build

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-scripts

# Apache + permissions
RUN a2enmod rewrite && \
    echo 'ServerName localhost' > /etc/apache2/conf-available/servername.conf && a2enconf servername && \
    sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf && \
    sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf && \
    sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/' /etc/apache2/sites-available/000-default.conf && \
    mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 755 storage bootstrap/cache

# Public .htaccess
COPY .htaccess /var/www/html/public/.htaccess

EXPOSE 8080

# Start Apache
CMD ["apache2-foreground"]
