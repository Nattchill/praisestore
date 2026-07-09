#!/bin/sh
set -e

cd /var/www/html

# Ensure APP_KEY is valid — fallback if Render env var is corrupted
if [ ${#APP_KEY} -lt 20 ]; then
  export APP_KEY="base64:hC6yq8PPJu73l7qW8PjJf8wBRxI6M8xtcUVd1EZ8zTo="
  echo "WARNING: APP_KEY was missing/corrupt, using fallback"
fi
echo "APP_KEY prefix: $(echo "$APP_KEY" | cut -c1-15)"

php artisan config:clear
php artisan cache:clear

# Run migrations and seed
php artisan migrate --force
php artisan db:seed --force

# Create storage symlink
php artisan storage:link --force

# Start supervisor (nginx + php-fpm)
exec /usr/bin/supervisord -c /etc/supervisord.conf
