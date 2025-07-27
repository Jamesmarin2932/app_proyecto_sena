#!/usr/bin/env bash

# Instala dependencias
composer install --no-dev --optimize-autoloader

# Da permisos
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
