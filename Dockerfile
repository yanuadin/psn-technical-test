FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www/psn-technical-test

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nano

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copy project files to default php-fpm www location
COPY . /var/www/psn-technical-test

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Prepare project psn-technical-test
RUN composer install \
    && composer dump-autoload

# Change permission var/www directory
RUN chown -R www-data:www-data /var/www/psn-technical-test/storage* && \
    chmod -R 775 /var/www/psn-technical-test/storage/*

USER $user