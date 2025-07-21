#!/bin/bash
set -e

echo "=== Laravel Application Startup ==="
echo "Timestamp: $(date)"
echo "Working directory: $(pwd)"
echo "User: $(whoami)"
echo

# Function to log errors
log_error() {
    echo "ERROR: $1" >&2
    if [ -f storage/logs/laravel.log ]; then
        echo "=== Recent Laravel logs ==="
        tail -20 storage/logs/laravel.log
    fi
    if [ -f /var/log/apache2/error.log ]; then
        echo "=== Recent Apache error logs ==="
        tail -20 /var/log/apache2/error.log
    fi
}

# Check basic Laravel functionality
echo "Testing Laravel installation..."
if ! php artisan --version; then
    log_error "Laravel artisan command failed"
    exit 1
fi

# Check .env file
echo "Checking .env file..."
if [ ! -f .env ]; then
    log_error ".env file not found"
    exit 1
fi

echo "APP_KEY value: $(grep APP_KEY .env || echo 'Not found')"

# Check storage permissions
echo "Checking storage permissions..."
if [ ! -w storage/logs ]; then
    log_error "storage/logs is not writable"
    exit 1
fi

# Clear caches with error handling
echo "Clearing caches..."
php artisan config:clear 2>&1 || log_error "Config clear failed"
php artisan route:clear 2>&1 || log_error "Route clear failed"
php artisan view:clear 2>&1 || log_error "View clear failed"
php artisan cache:clear 2>&1 || log_error "Cache clear failed"

# Set final permissions
echo "Setting final permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Test basic route
echo "Testing basic Laravel routes..."
php artisan route:list --compact 2>&1 || log_error "Route list failed"

# Check for common issues
echo "=== Environment Check ==="
echo "PHP Version: $(php -v | head -1)"
echo "Laravel Version: $(php artisan --version)"
echo "Current directory contents:"
ls -la | head -10
echo "Storage directory:"
ls -la storage/
echo "Bootstrap cache directory:"
ls -la bootstrap/cache/

echo "=== Starting Apache ==="
echo "If you see a 500 error, check the logs with:"
echo "docker exec -it <container-name> tail -f storage/logs/laravel.log"
echo "docker exec -it <container-name> tail -f /var/log/apache2/error.log"

# Start Apache with error output
exec apache2-foreground