FROM php:8.1.0-fpm

# set your user name, ex: user=bernardo

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd sockets

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
USER root
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home

# Install redis
RUN pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

RUN mkdir -p storage/framework/cache/data storage/app storage/framework/sessions storage/framework/views storage/logs
# Set working directory
WORKDIR /var/www

# Copy custom configurations PHP
COPY dev/php/custom.ini /usr/local/etc/php/conf.d/custom.ini

USER $user
