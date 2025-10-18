#!/usr/bin/env sh
set -euo pipefail

PORT="${PORT:-8080}"

# Ensure Apache listens on the provided PORT
printf "Listen %s\n" "$PORT" > /etc/apache2/ports.conf

# Ensure DocumentRoot points to public (already patched at build time)
# and required modules are enabled
apache2ctl -M | grep -q rewrite || a2enmod rewrite

# Log what port we’re using
echo "Starting Apache on port $PORT"

exec /usr/local/bin/apache2-foreground