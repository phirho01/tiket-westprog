#!/bin/sh
set -e

echo "=== STARTING LARAVEL RAILWAY BOOTSTRAP ==="
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "=== RUNNING MIGRATIONS AND SEEDERS ==="
php artisan migrate --force || true
php artisan db:seed --force || true

echo "=== STARTING LARAVEL SERVER ==="
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
