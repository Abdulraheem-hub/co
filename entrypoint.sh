#!/bin/bash
set -e

echo "Copying custom files from repository to /var/www/html..."
cp -rf * /var/www/html/

echo "Fixing permissions..."
chown -R www-data:www-data /var/www/html

echo "Starting PrestaShop..."
exec docker-php-entrypoint "$@"