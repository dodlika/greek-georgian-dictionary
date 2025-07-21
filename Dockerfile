# Use official PHP 8.2 image with Apache
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy composer files first
COPY composer.json composer.lock ./

# Create .env file with DEBUG enabled
RUN echo "APP_NAME=Laravel" > .env && \
    echo "APP_ENV=local" >> .env && \
    echo "APP_KEY=" >> .env && \
    echo "APP_DEBUG=true" >> .env && \
    echo "APP_URL=http://localhost" >> .env && \
    echo "" >> .env && \
    echo "DB_CONNECTION=mysql" >> .env && \
    echo "DB_HOST=127.0.0.1" >> .env && \
    echo "DB_PORT=3306" >> .env && \
    echo "DB_DATABASE=laravel" >> .env && \
    echo "DB_USERNAME=root" >> .env && \
    echo "DB_PASSWORD=" >> .env && \
    echo "" >> .env && \
    echo "CACHE_DRIVER=file" >> .env && \
    echo "SESSION_DRIVER=file" >> .env && \
    echo "QUEUE_CONNECTION=sync" >> .env && \
    echo "LOG_CHANNEL=single" >> .env && \
    echo "LOG_LEVEL=debug" >> .env

# Install PHP dependencies with error checking
RUN echo "Installing Composer dependencies..." && \
    composer install --no-dev --optimize-autoloader --no-scripts || \
    (echo "Composer install failed!" && exit 1)

# Copy existing application directory contents
COPY . /var/www/html

# Generate application key with error checking
RUN echo "Generating application key..." && \
    php artisan key:generate --force || \
    (echo "Key generation failed!" && exit 1)

# Create required directories and set permissions
RUN echo "Creating directories and setting permissions..." && \
    mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views && \
    mkdir -p bootstrap/cache && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 777 /var/www/html/storage && \
    chmod -R 777 /var/www/html/bootstrap/cache

# Test Laravel installation
RUN echo "Testing Laravel installation..." && \
    php artisan --version && \
    echo "Laravel version check passed!"

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Create Apache config for Laravel
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
        Options -Indexes +FollowSymLinks\n\
    </Directory>\n\
    <Directory /var/www/html>\n\
        AllowOverride All\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
    LogLevel debug\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Enable error reporting for debugging
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/error_reporting.ini && \
    echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/error_reporting.ini && \
    echo "log_errors = On" >> /usr/local/etc/php/conf.d/error_reporting.ini && \
    echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/error_reporting.ini

# Expose port 80
EXPOSE 80

# Create comprehensive startup script with error checking and logging
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "=== Laravel Application Startup ===\"\n\
echo "Timestamp: $(date)"\n\
echo "Working directory: $(pwd)"\n\
echo "User: $(whoami)"\n\
echo\n\
\n\
# Function to log errors\n\
log_error() {\n\
    echo "ERROR: $1" >&2\n\
    if [ -f storage/logs/laravel.log ]; then\n\
        echo "=== Recent Laravel logs ==="\n\
        tail -20 storage/logs/laravel.log\n\
    fi\n\
    if [ -f /var/log/apache2/error.log ]; then\n\
        echo "=== Recent Apache error logs ==="\n\
        tail -20 /var/log/apache2/error.log\n\
    fi\n\
}\n\
\n\
# Check basic Laravel functionality\n\
echo "Testing Laravel installation..."\n\
if ! php artisan --version; then\n\
    log_error "Laravel artisan command failed"\n\
    exit 1\n\
fi\n\
\n\
# Check .env file\n\
echo "Checking .env file..."\n\
if [ ! -f .env ]; then\n\
    log_error ".env file not found"\n\
    exit 1\n\
fi\n\
\n\
echo "APP_KEY value: $(grep APP_KEY .env || echo \"Not found\")"\n\
\n\
# Check storage permissions\n\
echo "Checking storage permissions..."\n\
if [ ! -w storage/logs ]; then\n\
    log_error "storage/logs is not writable"\n\
    exit 1\n\
fi\n\
\n\
# Clear caches with error handling\n\
echo "Clearing caches..."\n\
php artisan config:clear 2>&1 || log_error "Config clear failed"\n\
php artisan route:clear 2>&1 || log_error "Route clear failed"\n\
php artisan view:clear 2>&1 || log_error "View clear failed"\n\
php artisan cache:clear 2>&1 || log_error "Cache clear failed"\n\
\n\
# Set final permissions\n\
echo "Setting final permissions..."\n\
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache\n\
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache\n\
\n\
# Test basic route\n\
echo "Testing basic Laravel routes..."\n\
php artisan route:list --compact 2>&1 || log_error "Route list failed"\n\
\n\
# Check for common issues\n\
echo "=== Environment Check ==="\n\
echo "PHP Version: $(php -v | head -1)"\n\
echo "Laravel Version: $(php artisan --version)"\n\
echo "Current directory contents:"\n\
ls -la | head -10\n\
echo "Storage directory:"\n\
ls -la storage/\n\
echo "Bootstrap cache directory:"\n\
ls -la bootstrap/cache/\n\
\n\
echo "=== Starting Apache ==="\n\
echo "If you see a 500 error, check the logs with:"\n\
echo "docker exec -it <container-name> tail -f storage/logs/laravel.log"\n\
echo "docker exec -it <container-name> tail -f /var/log/apache2/error.log"\n\
\n\
# Start Apache with error output\n\
exec apache2-foreground' > /start.sh && chmod +x /start.sh

# Start Apache
CMD ["/start.sh"]