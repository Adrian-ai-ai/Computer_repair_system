FROM php:8.2-apache

# Install ONLY required system libs
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy application
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --no-dev --no-interaction --prefer-dist

# Fix permissions
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD apache2-foreground
