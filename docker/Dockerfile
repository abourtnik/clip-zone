FROM php:8.3.16-fpm-alpine

# Install dependencies
RUN apk --no-cache add curl git wget bash

# Add PHP extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions intl pdo pdo_mysql pcntl bcmath gd zip

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Set working directory
WORKDIR /var/www/html
