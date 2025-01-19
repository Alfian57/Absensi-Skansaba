# Base image: PHP 8.2 with FPM
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    supervisor \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libonig-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
       pdo \
       pdo_mysql \
       mbstring \
       exif \
       pcntl \
       bcmath \
       gd \
       opcache \
       intl \
       zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set Working Directory
WORKDIR /var/www/html

# Copy application dependencies and install
COPY . .
RUN composer install --optimize-autoloader --no-dev --no-interaction --prefer-dist

# Install ACL for managing default permissions
RUN apt-get update && apt-get install -y acl \
    && setfacl -R -m u:www-data:rwx /var/www/html/storage /var/www/html/bootstrap/cache \
    && setfacl -dR -m u:www-data:rwx /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Remove default Nginx configuration and add custom config
RUN rm /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default \
    && mv /var/www/html/docker/nginx/default.conf /etc/nginx/sites-available/default \
    && ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

# Copy Supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

# Expose port 80
EXPOSE 80

# Healthcheck
HEALTHCHECK --interval=30s --timeout=5s \
    CMD curl -f http://localhost/health || exit 1

# Command to run both PHP-FPM and Nginx
CMD ["supervisord", "-n"]