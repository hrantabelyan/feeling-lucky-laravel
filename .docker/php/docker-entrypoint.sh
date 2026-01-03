#!/bin/sh
set -e

# Fix permissions for storage and cache directories
if [ -d "/var/www/html/storage" ]; then
    chown -R www-data:www-data /var/www/html/storage
    chmod -R 775 /var/www/html/storage
fi

if [ -d "/var/www/html/bootstrap/cache" ]; then
    chown -R www-data:www-data /var/www/html/bootstrap/cache
    chmod -R 775 /var/www/html/bootstrap/cache
fi

# Check if .env file exists, and copy .env.example to .env if not
if [ ! -f .env ]; then
    echo ".env not found; copying .env.example → .env"
    cp .env.example .env
else
    echo ".env exists; skipping copy"
fi

echo "Composer install..."
if [ "$APP_ENV" = "production" ]; then
    composer install --prefer-dist --no-interaction --no-progress --optimize-autoloader --no-dev
else
    composer install --prefer-dist --no-interaction --no-progress --optimize-autoloader
fi

if ! grep -q "^APP_KEY=:" .env; then
    echo "Generating application key..."
    php artisan key:generate --force
else
    echo "Application key exists; skipping generation"
fi

if [ ! -L "/var/www/html/public/storage" ]; then
    echo "Creating storage link..."
    php artisan storage:link
else
    echo "Storage link exists; skipping creation"
fi

echo "Running migrations…"
php artisan migrate --force

echo "Caching configuration, routes, and views…"
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Execute the main container command
exec "$@"
