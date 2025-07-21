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
        echo "=== Laravel Log (last 20 lines) ==="
        tail -20 storage/logs/laravel.log
    fi
    if [ -f /var/log/apache2/error.log ]; then
        echo "=== Apache Error Log (last 20 lines) ==="
        tail -20 /var/log/apache2/error.log
    fi
}

# Configure Apache for Laravel (redundant safety check)
echo "=== Configuring Apache for Laravel ==="
cat << EOF > /etc/apache2/sites-available/000-default.conf
<VirtualHost *:80>
    DocumentRoot /var/www/html/public
    
    <Directory /var/www/html/public>
        AllowOverride All
        Require all granted
        DirectoryIndex index.php
        Options -Indexes +FollowSymLinks
    </Directory>
    
    <Directory /var/www/html>
        Options -Indexes
        AllowOverride None
        Require all denied
    </Directory>
    
    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

echo "=== Apache config updated ==="

# Verify public directory exists and has index.php
if [ ! -f "public/index.php" ]; then
    log_error "Laravel public/index.php not found!"
    echo "Directory contents:"
    ls -la
    echo "Public directory contents:"
    ls -la public/ 2>/dev/null || echo "Public directory doesn't exist"
    exit 1
fi

# Test Artisan
echo "Checking Laravel..."
if ! php artisan --version; then
    log_error "Artisan failed"
    exit 1
fi

# Show environment variables (without sensitive data)
echo "=== Environment Check ==="
echo "APP_ENV: ${APP_ENV:-not_set}"
echo "APP_DEBUG: ${APP_DEBUG:-not_set}"
echo "DB_CONNECTION: ${DB_CONNECTION:-not_set}"
echo "DB_HOST: ${DB_HOST:-not_set}"
echo "DB_PORT: ${DB_PORT:-not_set}"
echo "DB_DATABASE: ${DB_DATABASE:-not_set}"
echo "DB_USERNAME: ${DB_USERNAME:-not_set}"

# Check database connection with detailed output
echo "=== Testing database connection ==="
php artisan tinker --execute="
try {
    \$pdo = DB::connection()->getPdo();
    echo 'Database connected successfully' . PHP_EOL;
    echo 'Database name: ' . DB::connection()->getDatabaseName() . PHP_EOL;
    echo 'Driver name: ' . \$pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . PHP_EOL;
} catch (Exception \$e) {
    echo 'Database connection failed: ' . \$e->getMessage() . PHP_EOL;
    exit(1);
}
"

# Create .env file from environment variables for Laravel compatibility
echo "=== Creating .env file from environment variables ==="
cat > .env << EOF
APP_NAME="${APP_NAME:-Laravel}"
APP_ENV="${APP_ENV:-production}"
APP_KEY="${APP_KEY:-}"
APP_DEBUG="${APP_DEBUG:-false}"
APP_TIMEZONE="${APP_TIMEZONE:-UTC}"
APP_URL="${APP_URL:-http://localhost}"

LOG_CHANNEL="${LOG_CHANNEL:-stack}"
LOG_STACK="${LOG_STACK:-single}"
LOG_DEPRECATIONS_CHANNEL="${LOG_DEPRECATIONS_CHANNEL:-null}"
LOG_LEVEL="${LOG_LEVEL:-error}"

DB_CONNECTION="${DB_CONNECTION:-pgsql}"
DB_HOST="${DB_HOST:-}"
DB_PORT="${DB_PORT:-5432}"
DB_DATABASE="${DB_DATABASE:-}"
DB_USERNAME="${DB_USERNAME:-}"
DB_PASSWORD="${DB_PASSWORD:-}"

CACHE_STORE="${CACHE_STORE:-file}"
FILESYSTEM_DISK="${FILESYSTEM_DISK:-local}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-database}"
SESSION_DRIVER="${SESSION_DRIVER:-file}"
SESSION_LIFETIME="${SESSION_LIFETIME:-120}"

VITE_APP_NAME="${VITE_APP_NAME:-\${APP_NAME}}"
EOF

# Check if key exists; generate if missing
if [ -z "$APP_KEY" ] || ! echo "$APP_KEY" | grep -q "base64:"; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force || log_error "Key generation failed"
    # Read the generated key back into environment
    export APP_KEY=$(grep "APP_KEY=" .env | cut -d= -f2)
    echo "Generated APP_KEY: $APP_KEY"
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
chown -R www-data:www-data public
chmod -R 755 public

# Run migrations with verbose output
echo "=== Running database migrations ==="
php artisan migrate --force -v || log_error "Migrations failed"

# Check what tables exist
echo "=== Checking database tables ==="
php artisan tinker --execute="
try {
    \$tables = DB::select('SELECT table_name FROM information_schema.tables WHERE table_schema = ? ORDER BY table_name', [DB::connection()->getDatabaseName()]);
    echo 'Tables in database:' . PHP_EOL;
    foreach (\$tables as \$table) {
        echo '- ' . \$table->table_name . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Failed to list tables: ' . \$e->getMessage() . PHP_EOL;
}
"

# Check words table specifically
echo "=== Checking words table ==="
php artisan tinker --execute="
use App\Models\Word;
try {
    if (Schema::hasTable('words')) {
        echo 'Words table exists' . PHP_EOL;
        echo 'Table columns: ' . implode(', ', Schema::getColumnListing('words')) . PHP_EOL;
        \$count = Word::count();
        echo 'Current word count: ' . \$count . PHP_EOL;
        
        if (\$count > 0) {
            echo 'First word: ';
            \$first = Word::first();
            echo \$first->greek_word . ' -> ' . \$first->georgian_translation . PHP_EOL;
        }
    } else {
        echo 'Words table does not exist!' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error checking words table: ' . \$e->getMessage() . PHP_EOL;
}
"

# Clear existing duplicate data and re-seed properly
echo "=== Seeding check ==="
WORD_COUNT=$(php artisan tinker --execute="echo App\Models\Word::count();" 2>/dev/null || echo "ERROR")

if [ "$WORD_COUNT" = "ERROR" ]; then
    echo "Failed to check word count"
elif [ "$WORD_COUNT" -lt "10" ]; then  # If less than 10 words, re-seed (accounting for duplicates)
    echo "Current word count: $WORD_COUNT - clearing and re-seeding..."
    
    # Clear existing data
    php artisan tinker --execute="App\Models\Word::truncate(); echo 'Cleared existing words';" || echo "Failed to clear words"
    
    # Run seeder
    php artisan db:seed --class=WordSeeder --force -v || log_error "Seeder failed"
    
    # Verify seeding worked
    echo "=== Post-seeding verification ==="
    php artisan tinker --execute="
    \$count = App\Models\Word::count();
    echo 'Word count after seeding: ' . \$count . PHP_EOL;
    if (\$count > 0) {
        echo 'Sample words:' . PHP_EOL;
        App\Models\Word::take(5)->get()->each(function (\$word) {
            echo '- ' . \$word->greek_word . ' -> ' . \$word->georgian_translation . PHP_EOL;
        });
    }
    "
else
    echo "Sufficient words exist (count: $WORD_COUNT), skipping seeder"
fi

echo "=== Final checks ==="
echo "Apache document root check:"
grep -n "DocumentRoot" /etc/apache2/sites-available/000-default.conf
echo "Laravel public directory:"
ls -la public/ | head -5

echo "=== Starting Apache ==="
exec apache2-foreground