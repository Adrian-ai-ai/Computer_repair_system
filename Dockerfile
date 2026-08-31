# Stage 1 — frontend builder (node)
FROM node:24 AS node_builder
WORKDIR /app

# Cache node modules install by copying package files first
COPY package.json package-lock.json* ./
RUN npm ci --no-audit --no-fund

# Copy full repo and build assets (adjust if your build target differs)
COPY . .
RUN npm run build

# Stage 2 — install PHP dependencies with composer
FROM composer:2 AS composer_builder
WORKDIR /app

# Set composer memory limit to prevent out of memory errors
ENV COMPOSER_MEMORY_LIMIT=-1

# Copy composer files and install dependencies
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copy the rest of the project (so scripts, config are available for dump-autoload)
COPY . .
RUN composer dump-autoload --optimize

# Stage 3 — runtime image (PHP + Apache)
FROM php:8.2-apache

ENV DEBIAN_FRONTEND=noninteractive

# Install system deps and PHP extensions required by Laravel and common packages
RUN apt-get update \
 && apt-get install -y --no-install-recommends \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    wget \
    ca-certificates \
    gnupg \
 && docker-php-ext-configure gd --with-jpeg --with-freetype \
 && docker-php-ext-install -j"$(nproc)" pdo pdo_mysql pdo_pgsql zip gd mbstring bcmath exif pcntl opcache \
 && pecl install redis || true \
 && docker-php-ext-enable redis || true \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

# Enable Apache modules
RUN a2enmod rewrite headers

# Set working directory
WORKDIR /var/www/html

# Copy application code (source), vendor and built frontend assets from build stages
# Copy vendor from composer_builder (reduces time in runtime)
COPY --from=composer_builder /app/vendor ./vendor
# Copy the generated composer.lock if it exists
COPY --from=composer_builder /app/composer.lock ./composer.lock || true
# Copy built frontend files (Vite usually outputs to public/build)
COPY --from=node_builder /app/public/build ./public/build

# Copy the rest of the application (overwrites anything required)
COPY . .

# Ensure permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/public/build || true

EXPOSE 80

CMD ["apache2-foreground"]