FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install dependencies without scripts
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

# Create necessary directories
RUN mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache

# Set permissions
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Default retry settings for DB migrations on boot
ENV MIGRATE_RETRIES=20
ENV MIGRATE_SLEEP=5

# Create a startup script that starts Apache first, then runs migrations
RUN echo '#!/bin/bash' > /usr/local/bin/start.sh && \
    echo 'cd /var/www/html' >> /usr/local/bin/start.sh && \
    echo 'echo "Starting Apache..."' >> /usr/local/bin/start.sh && \
    echo 'apache2-foreground & APACHE_PID=$!' >> /usr/local/bin/start.sh && \
    echo 'echo "Ensuring storage symlink..."' >> /usr/local/bin/start.sh && \
    echo '[ -L public/storage ] || { rm -rf public/storage; php artisan storage:link || true; }' >> /usr/local/bin/start.sh && \
    echo 'echo "Apache started. Attempting database migrations and seeders..."' >> /usr/local/bin/start.sh && \
    echo 'RETRIES=${MIGRATE_RETRIES:-10}' >> /usr/local/bin/start.sh && \
    echo 'SLEEP=${MIGRATE_SLEEP:-5}' >> /usr/local/bin/start.sh && \
    echo 'for i in $(seq 1 "$RETRIES"); do' >> /usr/local/bin/start.sh && \
    echo '  php artisan migrate --path=database/migrations/2025_10_12_150000_fresh_start_migration.php --force && php artisan db:seed --force && break' >> /usr/local/bin/start.sh && \
    echo '  echo "Migration/seed attempt $i failed; retrying in $SLEEP seconds..."' >> /usr/local/bin/start.sh && \
    echo '  sleep "$SLEEP"' >> /usr/local/bin/start.sh && \
    echo 'done' >> /usr/local/bin/start.sh && \
    echo 'wait "$APACHE_PID"' >> /usr/local/bin/start.sh && \
    chmod +x /usr/local/bin/start.sh

# Configure Apache to serve from public directory
RUN a2enmod rewrite
# Suppress Apache ServerName warning
RUN echo 'ServerName localhost' > /etc/apache2/conf-available/servername.conf && a2enconf servername
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf
RUN sed -i 's/Listen 80/Listen 8080/' /etc/apache2/ports.conf && \
    sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/' /etc/apache2/sites-available/000-default.conf

# Copy .htaccess to public directory
COPY .htaccess /var/www/html/public/.htaccess

# Expose port
EXPOSE 8080

# Start with migrations
CMD ["/usr/local/bin/start.sh"]
