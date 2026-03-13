FROM php:8.3-cli

# Install system dependencies + CA certificates
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies (production only)
RUN composer install --optimize-autoloader --no-dev --no-interaction

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Set SSL CA for Aiven MySQL
ENV MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt

# Create startup script using PHP built-in server (no Apache = no MPM issues)
RUN printf '#!/bin/bash\n\
set -e\n\
PORT=${PORT:-8080}\n\
echo "=== Environment Debug ==="\n\
echo "DB_CONNECTION=$DB_CONNECTION"\n\
echo "DB_HOST=$DB_HOST"\n\
echo "DB_PORT=$DB_PORT"\n\
echo "DB_DATABASE=$DB_DATABASE"\n\
echo "DB_USERNAME=$DB_USERNAME"\n\
echo "APP_ENV=$APP_ENV"\n\
echo "PORT=$PORT"\n\
echo "========================"\n\
rm -f /var/www/html/bootstrap/cache/config.php\n\
php artisan migrate --force 2>&1 || echo "Migration skipped (non-fatal)"\n\
echo "Starting PHP server on port $PORT..."\n\
exec php artisan serve --host=0.0.0.0 --port=$PORT\n' > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
