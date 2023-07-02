#!/usr/bin/env bash
set -Eeuo pipefail

echo "Prd Entrypoint Script Started"

echo "Caching the framework bootstrap files"
php artisan optimize

# Check if the first argument is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
    set -- php-fpm "$@"
fi

exec "$@"
