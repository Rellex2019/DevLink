FROM php:8.2-fpm-alpine

RUN apk update && apk add \
    build-base \
    libzip-dev \
    zip \
    unzip \
    sqlite \
    sqlite-dev \
    curl

RUN docker-php-ext-install pdo pdo_sqlite pcntl

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs \
    && touch database/database.sqlite \
    && chmod -R 777 storage bootstrap/cache

COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

CMD ["php-fpm", "-R"]