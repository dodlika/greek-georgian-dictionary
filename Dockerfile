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

# Copy composer files first (for better layer caching)
COPY composer.json composer.lock ./

# Copy all files to check for .env.example
COPY . /tmp/app/

# Create .env file (either from .env.example or create minimal one)
RUN if [ -f /tmp/app/.env.example ]; then \
        cp /tmp/app/.env.example .env; \
    else \
        echo "APP_NAME=Laravel" > .env && \
        echo "APP_ENV=production" >> .env && \
        echo "APP_KEY=" >> .env && \
        echo "APP_DEBUG=false" >> .env && \
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
        echo "QUEUE_CONNECTION=sync" >> .env; \
    fi

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copy existing application directory contents (overwrite with actual files)
COPY . /var/www/html

# Generate application key
RUN php artisan key:generate --force

# Run the post-install scripts
RUN composer run-script post-install-cmd || true

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Create Apache config for Laravel
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
        Options Indexes FollowSymLinks\n\
    </Directory>\n\
    <Directory /var/www/html>\n\
        AllowOverride All\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80

# Create startup script
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
# Run Laravel optimization commands\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
\n\
# Start Apache\n\
exec apache2-foreground' > /start.sh && chmod +x /start.sh

# Start Apache
CMD ["/start.sh"]