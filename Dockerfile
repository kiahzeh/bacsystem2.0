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

# Create a startup script to run migrations
RUN echo '#!/bin/bash' > /usr/local/bin/start.sh && \
    echo 'cd /var/www/html' >> /usr/local/bin/start.sh && \
    echo 'echo "Starting Laravel application..."' >> /usr/local/bin/start.sh && \
    echo 'echo "Running fresh migration..."' >> /usr/local/bin/start.sh && \
    echo 'php artisan migrate --path=database/migrations/2025_10_12_150000_fresh_start_migration.php --force' >> /usr/local/bin/start.sh && \
    echo 'echo "Fresh migration completed. Running seeders..."' >> /usr/local/bin/start.sh && \
    echo 'php artisan db:seed --force' >> /usr/local/bin/start.sh && \
    echo 'echo "Seeders completed. Starting Apache..."' >> /usr/local/bin/start.sh && \
    echo 'exec apache2-foreground' >> /usr/local/bin/start.sh && \
    chmod +x /usr/local/bin/start.sh

# Configure Apache to serve from public directory
RUN a2enmod rewrite
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/apache2.conf

# Copy .htaccess to public directory
COPY .htaccess /var/www/html/public/.htaccess

# Expose port
EXPOSE 8080

# Start with migrations
CMD ["/usr/local/bin/start.sh"]
