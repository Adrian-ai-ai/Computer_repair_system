FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# Enable Apache rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP dependencies (NO scripts)
RUN composer install --no-dev --no-scripts --prefer-dist --no-interaction

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

# Start Apache only
CMD apache2-foreground
