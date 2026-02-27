# Use official PHP-Apache image
FROM php:8.2-apache

# Ensure only mpm_prefork is enabled (disable all others)
RUN a2dismod mpm_event mpm_worker mpm_itk || true \
    && a2enmod mpm_prefork

# Install system dependencies
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    apt-utils \
    build-essential \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libsodium-dev \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite (SAFE now)
RUN a2enmod rewrite

# PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_pgsql \
    zip \
    intl \
    bcmath \
    exif \
    sodium

# GD extension
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Configure Apache document root
RUN sed -i 's|/var/www/html|/var/www/html/public|g' \
    /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

# Copy project files
COPY . .

# Copy Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create storage directories and set permissions
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 storage bootstrap/cache

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 8080

CMD ["apache2-foreground"]