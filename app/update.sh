#!/bin/bash
cd /var/www/RizGroup/app
git reset --hard
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
