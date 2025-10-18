#!/usr/bin/env sh
set -euo pipefail

PORT="${PORT:-8080}"

# Ensure Apache listens on the provided PORT
printf "Listen %s\n" "$PORT" > /etc/apache2/ports.conf
printf "ServerName localhost\n" > /etc/apache2/conf-available/servername.conf
 a2enconf servername

# Align the default vhost to the runtime PORT (from 80)
sed -i "s#<VirtualHost \*:[0-9]\+#<VirtualHost *:${PORT}#" /etc/apache2/sites-available/000-default.conf

# Ensure DocumentRoot points to public (patched at build time)
# and required modules are enabled
apache2ctl -M | grep -q rewrite || a2enmod rewrite

# Log what port we’re using
echo "Starting Apache on port $PORT"

# Prepare Laravel cache and view compiled directories
mkdir -p /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

exec /usr/local/bin/apache2-foreground