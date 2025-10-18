# syntax=docker/dockerfile:1.5

# ---- ASSETS (Vite) ----
FROM node:20-alpine AS assets
WORKDIR /app
ARG RAILWAY_SERVICE_ID
ENV RAILWAY_SERVICE_ID=${RAILWAY_SERVICE_ID}

# Only copy package manifests first to leverage layer caching
COPY package.json package-lock.json ./

# Install deps (no BuildKit cache mounts to avoid Railway prefix check)
RUN npm ci --no-audit --no-fund

# Copy only what build needs
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY resources ./resources
COPY public ./public

ENV NODE_ENV=production
RUN npm run build

# ---- RUNTIME (Apache + PHP) ----
FROM php:8.2-apache
ARG RAILWAY_SERVICE_ID
ENV RAILWAY_SERVICE_ID=${RAILWAY_SERVICE_ID}

# System libs for PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpng-dev \
        libxml2-dev \
        libzip-dev \
        zip \
        unzip \
        libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Enable required PHP extensions (incl. zip for phpspreadsheet)
RUN docker-php-ext-configure zip \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install Composer deps before app copy for better layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copy application code
COPY . .

# Copy built Vite assets from assets stage
COPY --from=assets /app/public/build /var/www/html/public/build

# Apache config: serve Laravel from public and listen on 8080
RUN sed -i 's#DocumentRoot /var/www/html#DocumentRoot /var/www/html/public#' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's#<Directory /var/www/>#<Directory /var/www/html/public/>#' /etc/apache2/apache2.conf \
    && sed -i 's#AllowOverride None#AllowOverride All#' /etc/apache2/apache2.conf \
    && echo 'Listen 8080' > /etc/apache2/ports.conf \
    && a2enmod rewrite

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html/storage -type d -exec chmod 775 {} \; \
    && find /var/www/html/bootstrap/cache -type d -exec chmod 775 {} \;

EXPOSE 8080
CMD ["apache2-foreground"]
