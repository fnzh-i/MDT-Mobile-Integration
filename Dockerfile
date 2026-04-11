FROM php:8.5-fpm

# 1. Install System Dependencies (Added libonig-dev and others)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install PHP Extensions
# Added 'mbstring' and others - now they have the libraries they need
RUN docker-php-ext-install pdo_mysql mysqli mbstring exif pcntl bcmath gd

WORKDIR /var/www


# Install Composer (Laravel's heart)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy ONLY dependency files first (Better for caching)
COPY composer.json composer.lock ./

RUN composer install --no-scripts --no-autoloader --ignore-platform-reqs

COPY . .

# Finish Composer
RUN composer dump-autoload

ENV port=8000

EXPOSE 8000


 # Laravel's built-in server for development (Not for production use)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]