FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    libonig-dev \
    zip \
    libzip-dev \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    sqlite3 \
    libsqlite3-dev

# Install PHP extensions
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    docker-php-ext-install pdo_mysql pdo_sqlite mbstring zip exif pcntl && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Cleanup
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure PHP user
RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

# Prepare SQLite database
RUN mkdir -p /var/www/database && \
    touch /var/www/database/database.sqlite && \
    chown -R www:www /var/www/database && \
    chmod 755 /var/www/database && \
    chmod 644 /var/www/database/database.sqlite

# Switch to application user
USER www

# Expose port and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]