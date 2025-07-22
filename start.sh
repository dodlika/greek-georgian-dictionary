#!/bin/bash
set -e

echo "=== Laravel Application Startup ==="
echo "Timestamp: $(date)"

# Wait for database to be ready
echo "Waiting for database..."
for i in {1..30}; do
    if php artisan tinker --execute="DB::connection()->getPdo(); echo 'DB Ready';" 2>/dev/null; then
        echo "Database connection successful"
        break
    fi
    echo "Waiting for database... ($i/30)"
    sleep 2
done

# Set proper permissions
echo "Setting permissions..."
chown -R www-data:www-data storage bootstrap/cache public
chmod -R 775 storage bootstrap/cache
chmod -R 755 public

# Clear caches
echo "Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Generate key if needed
if [ -z "$APP_KEY" ]; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Check for users table (needed for authentication)
echo "Checking authentication setup..."
php artisan tinker --execute="
if (Schema::hasTable('users')) {
    echo 'Users table exists - authentication should work' . PHP_EOL;
} else {
    echo 'ERROR: Users table missing! Authentication will fail.' . PHP_EOL;
    exit(1);
}
"

# Seed if needed (only for your words, not users)
WORD_COUNT=$(php artisan tinker --execute="echo App\Models\Word::count();" 2>/dev/null || echo "0")
if [ "$WORD_COUNT" -lt "10" ]; then
    echo "Seeding dictionary data..."
    php artisan db:seed --class=WordSeeder --force
fi
 php artisan db:seed --class=UserSeeder --force
echo "=== Starting Apache ==="
exec apache2-foreground