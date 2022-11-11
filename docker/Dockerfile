FROM php:8.1.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    gnupg \
    unzip \
    libpq-dev \
    libjpeg-dev \
    libfreetype6-dev

# Install PHP extensions
RUN docker-php-ext-install mbstring exif pcntl bcmath pdo_mysql zip
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install gd

COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install NodeJS
RUN curl -sL https://deb.nodesource.com/setup_16.x  | bash -
RUN apt-get -y install nodejs

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Set working directory
WORKDIR /var/www/html
