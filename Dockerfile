FROM php:8.3-apache

# Install system dependencies + CA certificates
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Fix Apache MPM conflict: disable mpm_event, enable mpm_prefork
RUN a2dismod mpm_event && a2enmod mpm_prefork && a2enmod rewrite

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

# Configure Apache to use Laravel public directory
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN printf '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' >> /etc/apache2/apache2.conf

# Set SSL CA for Aiven MySQL
ENV MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt

# Create startup script
RUN printf '#!/bin/bash\n\
set -e\n\
PORT=${PORT:-80}\n\
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf\n\
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf\n\
# Remove cached config if exists\n\
rm -f /var/www/html/bootstrap/cache/config.php\n\
# Debug: print DB env vars\n\
echo "DB_CONNECTION=$DB_CONNECTION"\n\
echo "DB_HOST=$DB_HOST"\n\
echo "DB_PORT=$DB_PORT"\n\
echo "DB_DATABASE=$DB_DATABASE"\n\
echo "Listening on port $PORT"\n\
php artisan migrate --force 2>&1 || echo "Migration skipped"\n\
exec apache2-foreground\n' > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["/usr/local/bin/start.sh"]
