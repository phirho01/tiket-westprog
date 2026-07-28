#!/bin/sh
echo "=== STARTING LARAVEL BOOTSTRAP ON RAILWAY ==="
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "=== RUNNING FRESH MIGRATIONS AND SEEDERS ==="
php artisan migrate:fresh --force --seed || true

echo "=== STARTING LARAVEL SERVE SERVER ==="
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
