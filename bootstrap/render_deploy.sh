#!/bin/bash
set -e

echo "=== Running migrations ==="
php artisan migrate --force

echo "=== Running seeders ==="
php artisan db:seed --force

echo "=== Caching ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Storage link ==="
php artisan storage:link

echo "=== Deploy complete ==="
