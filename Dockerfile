# Use Ubuntu 22.04 base image for better control
FROM ubuntu:22.04

# Set environment variables to avoid interactive prompts
ENV DEBIAN_FRONTEND=noninteractive

# Add PHP PPA repository for PHP 8.2
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    software-properties-common \
    ca-certificates \
    lsb-release \
    apt-transport-https \
    gnupg \
    && add-apt-repository ppa:ondrej/php -y \
    && apt-get update

# Install system dependencies, Apache, and PHP
RUN apt-get install -y --no-install-recommends \
    apache2 \
    php8.2 \
    php8.2-fpm \
    php8.2-cli \
    php8.2-pgsql \
    php8.2-pdo \
    php8.2-zip \
    php8.2-intl \
    php8.2-bcmath \
    php8.2-exif \
    php8.2-sodium \
    php8.2-gd \
    php8.2-mbstring \
    php8.2-xml \
    php8.2-curl \
    php8.2-tokenizer \
    php8.2-ctype \
    php8.2-fileinfo \
    php8.2-dom \
    php8.2-simplexml \
    libapache2-mod-php8.2 \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libsodium-dev \
    build-essential \
    apt-utils \
    && rm -rf /var/lib/apt/lists/*

# Configure PHP FPM
RUN sed -i 's/;clear_env = no/clear_env = no/' /etc/php/8.2/fpm/pool.d/www.conf \
    && sed -i 's/;pm.max_children = 5/pm.max_children = 50/' /etc/php/8.2/fpm/pool.d/www.conf \
    && sed -i 's/;pm.start_servers = 2/pm.start_servers = 5/' /etc/php/8.2/fpm/pool.d/www.conf \
    && sed -i 's/;pm.min_spare_servers = 1/pm.min_spare_servers = 2/' /etc/php/8.2/fpm/pool.d/www.conf \
    && sed -i 's/;pm.max_spare_servers = 3/pm.max_spare_servers = 5/' /etc/php/8.2/fpm/pool.d/www.conf

# Configure Apache to use PHP FPM and ensure only mpm_prefork
RUN a2enmod rewrite \
    && a2enmod proxy_fcgi \
    && a2enmod fcgid \
    && a2dismod mpm_event mpm_worker mpm_itk || true \
    && a2enmod mpm_prefork \
    && a2disconf php8.2-fpm \
    && a2enconf php8.2-fpm

# Configure Apache ports
RUN echo "Listen 80" > /etc/apache2/ports.conf

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer

# Create storage directories and set permissions
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 777 storage bootstrap/cache

# Install PHP dependencies
RUN /usr/local/bin/composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Create Apache virtual host configuration using echo
RUN echo "<VirtualHost *:80>" > /etc/apache2/sites-available/000-default.conf \
    && echo "    ServerName localhost" >> /etc/apache2/sites-available/000-default.conf \
    && echo "    DocumentRoot /var/www/html/public" >> /etc/apache2/sites-available/000-default.conf \
    && echo "    " >> /etc/apache2/sites-available/000-default.conf \
    && echo "    <Directory /var/www/html/public>" >> /etc/apache2/sites-available/000-default.conf \
    && echo "        Options Indexes FollowSymLinks" >> /etc/apache2/sites-available/000-default.conf \
    && echo "        AllowOverride All" >> /etc/apache2/sites-available/000-default.conf \
    && echo "        Require all granted" >> /etc/apache2/sites-available/000-default.conf \
    && echo "    </Directory>" >> /etc/apache2/sites-available/000-default.conf \
    && echo "    " >> /etc/apache2/sites-available/000-default.conf \
    && echo "    ErrorLog \${APACHE_LOG_DIR}/error.log" >> /etc/apache2/sites-available/000-default.conf \
    && echo "    CustomLog \${APACHE_LOG_DIR}/access.log combined" >> /etc/apache2/sites-available/000-default.conf \
    && echo "    " >> /etc/apache2/sites-available/000-default.conf \
    && echo "    # Proxy PHP requests to PHP-FPM" >> /etc/apache2/sites-available/000-default.conf \
    && echo "    <FilesMatch \.php$>" >> /etc/apache2/sites-available/000-default.conf \
    && echo "        SetHandler \"proxy:fcgi://127.0.0.1:9000\"" >> /etc/apache2/sites-available/000-default.conf \
    && echo "    </FilesMatch>" >> /etc/apache2/sites-available/000-default.conf \
    && echo "    " >> /etc/apache2/sites-available/000-default.conf \
    && echo "    ProxyPassMatch ^/(.*\.php(/.*)?$ fcgi://127.0.0.1:9000/var/www/html/public/\$1" >> /etc/apache2/sites-available/000-default.conf \
    && echo "    DirectoryIndex index.php index.html" >> /etc/apache2/sites-available/000-default.conf \
    && echo "</VirtualHost>" >> /etc/apache2/sites-available/000-default.conf

# Create startup script using echo
RUN echo "#!/bin/bash" > /start.sh \
    && echo "# Start PHP-FPM" >> /start.sh \
    && echo "service php8.2-fpm start" >> /start.sh \
    && echo " " >> /start.sh \
    && echo "# Start Apache in foreground" >> /start.sh \
    && echo "apache2-foreground" >> /start.sh \
    && chmod +x /start.sh

# Expose port 80
EXPOSE 80

# Start both services
CMD ["/start.sh"]
