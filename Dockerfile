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

# Add Node.js 20 repository
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get update

# Install system dependencies, Apache, and PHP
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    apache2 \
    php8.2 \
    php8.2-fpm \
    php8.2-cli \
    php8.2-pgsql \
    php8.2-pdo \
    php8.2-sqlite3 \
    php8.2-zip \
    php8.2-intl \
    php8.2-bcmath \
    php8.2-exif \
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
    curl \
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

# Install Node.js 20 and npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest

# Configure PHP FPM
RUN sed -i 's/;clear_env = no/clear_env = no/' /etc/php/8.2/fpm/pool.d/www.conf \
    && sed -i 's/;pm.max_children = 5/pm.max_children = 50/' /etc/php/8.2/fpm/pool.d/www.conf \
    && sed -i 's/;pm.start_servers = 2/pm.start_servers = 5/' /etc/php/8.2/fpm/pool.d/www.conf \
    && sed -i 's/;pm.min_spare_servers = 1/pm.min_spare_servers = 2/' /etc/php/8.2/fpm/pool.d/www.conf \
    && sed -i 's/;pm.max_spare_servers = 3/pm.max_spare_servers = 5/' /etc/php/8.2/fpm/pool.d/www.conf \
    && sed -i 's/listen = \/run\/php\/php8.2-fpm.sock/listen = 127.0.0.1:9000/' /etc/php/8.2/fpm/pool.d/www.conf

# Configure Apache to use PHP FPM and ensure only mpm_prefork
RUN a2enmod rewrite \
    && a2enmod proxy_fcgi \
    && a2enmod fcgid \
    && a2dismod mpm_event mpm_worker mpm_itk || true \
    && a2enmod mpm_prefork \
    && a2disconf php8.2-fpm \
    && a2enconf php8.2-fpm

# Configure Apache ports and add ServerName
RUN echo "Listen 80" > /etc/apache2/ports.conf \
    && echo "ServerName localhost" >> /etc/apache2/apache2.conf

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

# Create .env file if it doesn't exist and set permissions
RUN if [ ! -f .env ]; then cp .env.example .env; fi \
    && chmod 644 .env \
    && php artisan key:generate --force

# Set production environment variables for proper asset loading
RUN sed -i 's/APP_ENV=local/APP_ENV=production/' .env \
    && sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env \
    && sed -i 's/APP_URL=http:\/\/localhost/APP_URL=https:\/\/computerrepairsystem-production.up.railway.app\//' .env

# Laravel optimizations (without database migrations for now)
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan storage:link

# Build frontend assets
RUN npm install \
    && npm run build

# Set proper permissions for build directory and verify assets
RUN chown -R www-data:www-data public/build \
    && chmod -R 755 public/build \
    && echo "Checking if manifest.json exists:" \
    && ls -la public/build/ \
    && echo "Checking manifest.json content:" \
    && cat public/build/manifest.json || echo "manifest.json not found"

# Clear and rebuild caches with production settings
RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan optimize

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
    && echo "    # Serve static assets directly" >> /etc/apache2/sites-available/000-default.conf \
    && echo "    <Directory /var/www/html/public/build>" >> /etc/apache2/sites-available/000-default.conf \
    && echo "        Options -Indexes" >> /etc/apache2/sites-available/000-default.conf \
    && echo "        AllowOverride None" >> /etc/apache2/sites-available/000-default.conf \
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
    && echo "    ProxyPassMatch ^/(.*\.php(/.*)?)$ fcgi://127.0.0.1:9000/var/www/html/public/\$1" >> /etc/apache2/sites-available/000-default.conf \
    && echo "    DirectoryIndex index.php index.html" >> /etc/apache2/sites-available/000-default.conf \
    && echo "</VirtualHost>" >> /etc/apache2/sites-available/000-default.conf

# Create startup script using echo
RUN echo "#!/bin/bash" > /start.sh \
    && echo "# Start PHP-FPM" >> /start.sh \
    && echo "service php8.2-fpm start" >> /start.sh \
    && echo "sleep 2" >> /start.sh \
    && echo "# Check if PHP-FPM is running" >> /start.sh \
    && echo "if ! pgrep -f 'php-fpm' > /dev/null; then" >> /start.sh \
    && echo "    echo 'PHP-FPM failed to start, exiting...'" >> /start.sh \
    && echo "    exit 1" >> /start.sh \
    && echo "fi" >> /start.sh \
    && echo "echo 'PHP-FPM is running'" >> /start.sh \
    && echo " " >> /start.sh \
    && echo "# Use correct Railway internal PostgreSQL from DATABASE_URL" >> /start.sh \
    && echo "echo '=== USING CORRECT RAILWAY DATABASE ==='" >> /start.sh \
    && echo "sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=pgsql/' .env" >> /start.sh \
    && echo "sed -i 's/# DB_HOST=127.0.0.1/DB_HOST=postgres.railway.internal/' .env" >> /start.sh \
    && echo "sed -i 's/# DB_PORT=3306/DB_PORT=5432/' .env" >> /start.sh \
    && echo "sed -i 's/# DB_DATABASE=laravel/DB_DATABASE=railway/' .env" >> /start.sh \
    && echo "sed -i 's/# DB_USERNAME=root/DB_USERNAME=postgres/' .env" >> /start.sh \
    && echo "sed -i 's/# DB_PASSWORD=/DB_PASSWORD=VaZwHfotxlquvIJcxNQSAAzRAVQSmjaI/' .env" >> /start.sh \
    && echo " " >> /start.sh \
    && echo "# Test PostgreSQL connection with correct Railway host" >> /start.sh \
    && echo "echo '=== TESTING POSTGRESQL CONNECTION ==='" >> /start.sh \
    && echo "echo 'Using Railway internal host: postgres.railway.internal:5432'" >> /start.sh \
    && echo "if timeout 10 php artisan tinker --execute='DB::connection()->getPdo(); echo \"SUCCESS\";' 2>/dev/null; then" >> /start.sh \
    && echo "    echo 'PostgreSQL connection successful!'" >> /start.sh \
    && echo "else" >> /start.sh \
    && echo "    echo 'PostgreSQL connection failed, switching to SQLite...'" >> /start.sh \
    && echo "    sed -i 's/DB_CONNECTION=pgsql/DB_CONNECTION=sqlite/' .env" >> /start.sh \
    && echo "    sed -i 's/DB_HOST=.*/# DB_HOST=127.0.0.1/' .env" >> /start.sh \
    && echo "    sed -i 's/DB_PORT=.*/# DB_PORT=3306/' .env" >> /start.sh \
    && echo "    sed -i 's/DB_DATABASE=.*/DB_DATABASE=database.sqlite/' .env" >> /start.sh \
    && echo "    sed -i 's/DB_USERNAME=.*/# DB_USERNAME=/' .env" >> /start.sh \
    && echo "    sed -i 's/DB_PASSWORD=.*/# DB_PASSWORD=/' .env" >> /start.sh \
    && echo "    touch database/database.sqlite && chmod 666 database/database.sqlite" >> /start.sh \
    && echo "fi" >> /start.sh \
    && echo " " >> /start.sh \
    && echo "# Check if assets exist and build if needed" >> /start.sh \
    && echo "echo '=== CHECKING ASSETS ==='" >> /start.sh \
    && echo "if [ ! -f 'public/build/assets/app.css' ]; then" >> /start.sh \
    && echo "    echo 'Assets not found, building with Vite...'" >> /start.sh \
    && echo "    npm run build" >> /start.sh \
    && echo "else" >> /start.sh \
    && echo "    echo 'Assets already exist'" >> /start.sh \
    && echo "fi" >> /start.sh \
    && echo " " >> /start.sh \
    && echo "# List built assets for debugging" >> /start.sh \
    && echo "echo '=== BUILT ASSETS ==='" >> /start.sh \
    && echo "ls -la public/build/ 2>/dev/null || echo 'No build directory found'" >> /start.sh \
    && echo "ls -la public/build/assets/ 2>/dev/null || echo 'No assets directory found'" >> /start.sh \
    && echo " " >> /start.sh \
    && echo "# Clear all caches and rebuild" >> /start.sh \
    && echo "echo '=== CLEARING ALL CACHES ==='" >> /start.sh \
    && echo "php artisan config:clear" >> /start.sh \
    && echo "php artisan route:clear" >> /start.sh \
    && echo "php artisan view:clear" >> /start.sh \
    && echo "php artisan cache:clear" >> /start.sh \
    && echo " " >> /start.sh \
    && echo "# Enable detailed error logging" >> /start.sh \
    && echo "sed -i 's/APP_DEBUG=false/APP_DEBUG=true/' .env" >> /start.sh \
    && echo "sed -i 's/LOG_LEVEL=debug/LOG_LEVEL=debug/' .env" >> /start.sh \
    && echo " " >> /start.sh \
    && echo "# Rebuild caches" >> /start.sh \
    && echo "echo '=== REBUILDING CACHES ==='" >> /start.sh \
    && echo "php artisan config:cache" >> /start.sh \
    && echo "php artisan route:cache" >> /start.sh \
    && echo "php artisan view:cache" >> /start.sh \
    && echo " " >> /start.sh \
    && echo "# Ensure storage link exists" >> /start.sh \
    && echo "echo '=== CREATING STORAGE LINKS ==='" >> /start.sh \
    && echo "php artisan storage:link" >> /start.sh \
    && echo " " >> /start.sh \
    && echo "# Debug: Show current database configuration" >> /start.sh \
    && echo "echo '=== FINAL DATABASE CONFIGURATION ==='" >> /start.sh \
    && echo "grep 'DB_' .env | grep -v '^#'" >> /start.sh \
    && echo " " >> /start.sh \
    && echo "# Run migrations with error handling" >> /start.sh \
    && echo "echo '=== RUNNING MIGRATIONS ==='" >> /start.sh \
    && echo "php artisan migrate --force || echo 'Migrations failed - check error above'" >> /start.sh \
    && echo " " >> /start.sh \
    && echo "# Start Apache in foreground" >> /start.sh \
    && echo "echo '=== STARTING APACHE ==='" >> /start.sh \
    && echo "echo 'Application logs: /var/www/html/storage/logs/laravel.log'" >> /start.sh \
    && echo "echo 'Apache logs: /var/log/apache2/error.log'" >> /start.sh \
    && echo "echo 'Application URL: https://computerrepairsystem-production.up.railway.app/'" >> /start.sh \
    && echo "apache2ctl -D FOREGROUND" >> /start.sh \
    && chmod +x /start.sh

# Expose port 80
EXPOSE 80

# Start both services
CMD ["/start.sh"]
