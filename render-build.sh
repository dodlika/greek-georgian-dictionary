#!/bin/bash
# Build script for Render deployment

echo "Starting build process..."

# Install dependencies
composer install --no-dev --optimize-autoloader

echo "Composer install completed"

# Generate application key if not exists
php artisan key:generate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Laravel optimizations completed"

# Run migrations
php artisan migrate --force

echo "Database migrations completed"



echo "Build process completed successfully!"