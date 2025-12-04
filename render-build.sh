#!/bin/bash
# Render build script for Laravel

set -e

echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

echo "Creating storage directories..."
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

echo "Setting permissions..."
chmod -R 775 storage bootstrap/cache

echo "Creating SQLite database..."
touch database/database.sqlite
chmod 664 database/database.sqlite

echo "Running migrations..."
php artisan migrate --force

echo "Build complete!"
