FROM php:8.2-fpm-alpine

WORKDIR /var/www

RUN apk update && apk add \
    build-base \
    libzip-dev \
    zip \
    unzip \
    sqlite \
    sqlite-dev \
    && docker-php-ext-install pdo pdo_sqlite pcntl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.json composer.lock ./


COPY . .

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs \
    && touch database/database.sqlite \
    && chown -R www-data:www-data /var/www \
    && find /var/www -type d -exec chmod 775 {} \; \
    && find /var/www -type f -exec chmod 664 {} \; \
    && chmod -R 777 storage bootstrap/cache

EXPOSE 9000
CMD ["sh", "-c", "php artisan migrate --force && php-fpm"]