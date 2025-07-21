#!/bin/bash
set -e

echo "=== Laravel Application Startup ==="
echo "Timestamp: $(date)"
echo "Working directory: $(pwd)"
echo "User: $(whoami)"
echo

# Error logging function
log_error() {
    echo "ERROR: $1" >&2
    if [ -f storage/logs/laravel.log ]; then
        echo "=== Laravel Log ==="
        tail -20 storage/logs/laravel.log
    fi
    if [ -f /var/log/apache2/error.log ]; then
        echo "=== Apache Error Log ==="
        tail -20 /var/log/apache2/error.log
    fi
}

# Test Artisan
echo "Checking Laravel..."
if ! php artisan --version; then
    log_error "Artisan failed"
    exit 1
fi

# Check if key exists; generate if missing
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force || log_error "Key generation failed"
fi

# Clear cached config to use runtime env vars
echo "Clearing caches..."
php artisan config:clear || log_error "Config clear failed"
php artisan route:clear || log_error "Route clear failed"
php artisan view:clear || log_error "View clear failed"
php artisan cache:clear || log_error "Cache clear failed"

# Set final permissions
echo "Fixing permissions..."
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Optional: Run migrations if using PostgreSQL
echo "Running database migrations..."
php artisan migrate --force || log_error "Migrations failed"

echo "Starting Apache..."
exec apache2-foreground
