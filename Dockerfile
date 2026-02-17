FROM php:8.2-apache

# System packages
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev \
    libicu-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        zip \
        intl \
        gd \
        bcmath \
        exif \
        sodium

# Enable Apache rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy source
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN php -m
# Install PHP dependencies
RUN composer install --no-dev --no-scripts --prefer-dist --no-interaction

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD apache2-foreground
