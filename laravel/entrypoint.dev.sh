#!/usr/bin/env bash
set -Eeuo pipefail

echo "Dev Entrypoint Script Started"

# Check if composer dependencies are already installed
if [ ! -f "vendor/autoload.php" ]; then
    echo "Running composer install"
    composer install
fi

echo "Seeding the database"
php artisan db:wipe --database=pgsql
php artisan migrate:fresh --seed

echo "Starting php-fpm daemon"
exec "$@"
