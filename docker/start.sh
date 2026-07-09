#!/bin/sh
set -e

cd /var/www/html

# Debug: print first 10 chars of APP_KEY to confirm it's loaded
echo "APP_KEY prefix: $(echo "$APP_KEY" | cut -c1-10)"

# Clear any cached config to ensure env vars are read fresh
php artisan config:clear
php artisan cache:clear

# Run migrations and seed
php artisan migrate --force
php artisan db:seed --force

# Start supervisor (nginx + php-fpm)
exec /usr/bin/supervisord -c /etc/supervisord.conf
