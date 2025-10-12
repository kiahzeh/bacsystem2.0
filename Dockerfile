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
    echo 'echo "Testing database connection..."' >> /usr/local/bin/start.sh && \
    echo 'php artisan tinker --execute="DB::connection()->getPdo(); echo \"Database connected successfully\";" || echo "Database connection failed"' >> /usr/local/bin/start.sh && \
    echo 'echo "Setting up Laravel..."' >> /usr/local/bin/start.sh && \
    echo 'php artisan config:cache || echo "Config cache failed"' >> /usr/local/bin/start.sh && \
    echo 'php artisan route:cache || echo "Route cache failed"' >> /usr/local/bin/start.sh && \
    echo 'php artisan view:cache || echo "View cache failed"' >> /usr/local/bin/start.sh && \
    echo 'echo "Running migrations..."' >> /usr/local/bin/start.sh && \
    echo 'php artisan migrate --force || echo "Migration failed, continuing..."' >> /usr/local/bin/start.sh && \
    echo 'echo "Running seeders..."' >> /usr/local/bin/start.sh && \
    echo 'php artisan db:seed --force || echo "Seeding failed, continuing..."' >> /usr/local/bin/start.sh && \
    echo 'echo "Starting Apache..."' >> /usr/local/bin/start.sh && \
    echo 'apache2-foreground' >> /usr/local/bin/start.sh && \
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
