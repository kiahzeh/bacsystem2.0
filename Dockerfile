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

# Configure Apache
RUN a2enmod rewrite
COPY .htaccess /var/www/html/.htaccess

# Expose port
EXPOSE 8080

# Start Apache
CMD ["apache2-foreground"]
