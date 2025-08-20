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
# Skip cache:clear due to permission issues in Railway
echo "Skipping cache:clear due to Railway permissions"

# APP_KEY is set via Railway environment variables

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Check for users table and create admin user
echo "Checking authentication setup..."
php artisan tinker --execute="
if (Schema::hasTable('users')) {
    echo 'Users table exists' . PHP_EOL;
    
    // Create admin user if it doesn't exist
    \$adminEmail = 'admin@example.com';
    \$user = App\Models\User::where('email', \$adminEmail)->first();
    
    if (!\$user) {
        App\Models\User::create([
            'name' => 'Admin',
            'email' => \$adminEmail,
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        echo 'Created admin user: admin@example.com / password123' . PHP_EOL;
    } else {
        echo 'Admin user already exists' . PHP_EOL;
    }
} else {
    echo 'ERROR: Users table missing! Authentication will fail.' . PHP_EOL;
    exit(1);
}
"

# Run seeders (one time only)
echo "Checking and running seeders..."

# Check if words table has any data
WORD_COUNT=$(php artisan tinker --execute="echo App\Models\Word::count();" 2>/dev/null || echo "0")
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::where('email', '!=', 'admin@example.com')->count();" 2>/dev/null || echo "0")

if [ "$WORD_COUNT" = "0" ] || [ "$USER_COUNT" = "0" ]; then
    echo "Running seeders..."
    echo "Words: $WORD_COUNT, Users (non-admin): $USER_COUNT"
    
    if [ "$USER_COUNT" = "0" ]; then
        echo "Seeding users..."
        php artisan db:seed --class=UserSeeder --force
    fi
    
    if [ "$WORD_COUNT" = "0" ]; then
        echo "Seeding words..."
        php artisan db:seed --class=WordSeeder --force
    fi
    
    echo "Seeding completed!"
else
    echo "Data already exists - Words: $WORD_COUNT, Users: $USER_COUNT - skipping seeders"
fi



# Show created users for reference
echo "=== Available Users ==="
php artisan tinker --execute="
App\Models\User::all(['name', 'email'])->each(function(\$user) {
    echo 'User: ' . \$user->name . ' (' . \$user->email . ')' . PHP_EOL;
});
"

echo "=== Starting PHP Server on Port ${PORT:-8080} ==="
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}