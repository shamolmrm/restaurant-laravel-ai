FROM php:8.2-apache

# Install php-extension-installer for reliable PHP extension installation
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install PHP extensions (installer handles all native library deps)
RUN install-php-extensions pdo_mysql pdo_pgsql pdo_sqlite mbstring xml bcmath gd intl zip opcache

# Install system packages
RUN apt-get update -y && apt-get install -y git unzip curl && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite headers

# Configure Apache document root to Laravel public/
RUN echo '<VirtualHost *:80>' > /etc/apache2/sites-available/000-default.conf && \
    echo '    DocumentRoot /var/www/html/public' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    <Directory /var/www/html/public>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        AllowOverride All' >> /etc/apache2/sites-available/000-default.conf && \
    echo '        Require all granted' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    </Directory>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '</VirtualHost>' >> /etc/apache2/sites-available/000-default.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Use build-only .env (SQLite, no real DB) to satisfy any artisan calls during composer
COPY .env.docker .env

# Install PHP dependencies, skip post-install scripts (no DB commands run during build)
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

# Ensure required storage directories exist with correct permissions
RUN mkdir -p \
    storage/logs \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache/data \
    bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

EXPOSE 80

# At runtime Render injects real env vars — remove the build .env so runtime vars take over
CMD rm -f .env bootstrap/cache/config.php bootstrap/cache/routes*.php && \
    php artisan storage:link --force 2>/dev/null; \
    php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    apache2-foreground
