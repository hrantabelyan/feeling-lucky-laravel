#!/bin/sh
set -e

# Fix permissions for storage and cache directories
if [ -d "/var/www/html/storage" ]; then
    chmod -R 777 /var/www/html/storage
fi

if [ -d "/var/www/html/bootstrap/cache" ]; then
    chmod -R 777 /var/www/html/bootstrap/cache
fi

# Check if .env file exists, and copy .env.example to .env if not
if [ ! -f .env ]; then
    echo ".env not found; copying .env.example → .env"
    cp .env.example .env
else
    echo ".env exists; skipping copy"
fi

echo "Composer install..."
composer install

echo "Generating application key..."
php artisan key:generate

php artisan storage:link

echo "Running migrations…"
php artisan migrate

# Execute the main container command
exec "$@"
