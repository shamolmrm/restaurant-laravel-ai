FROM php:8.2-apache

# Use php-extension-installer for reliable extension installation
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install required PHP extensions (handles all dependencies automatically)
RUN install-php-extensions \
    pdo_mysql \
    pdo_pgsql \
    mbstring \
    xml \
    bcmath \
    gd \
    intl \
    zip \
    opcache

# Install system tools
RUN apt-get update -y && apt-get install -y git unzip curl && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite headers

# Set document root to Laravel public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf 2>/dev/null; \
    printf '\n<Directory /var/www/html/public>\n\tAllowOverride All\n\tRequire all granted\n</Directory>\n' \
    >> /etc/apache2/apache2.conf

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Create temp .env so artisan commands work during build
RUN cp .env.example .env && php artisan key:generate --force

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Fix permissions
RUN mkdir -p storage/logs storage/framework/{sessions,views,cache/data} bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

RUN php artisan storage:link 2>/dev/null || true

EXPOSE 80

CMD php artisan config:clear && \
    php artisan migrate --force && \
    php artisan db:seed --force && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    apache2-foreground
