FROM php:7.4.16-fpm

COPY --from=composer:2.0.11 /usr/bin/composer /usr/bin/composer

RUN apt-get update
RUN apt-get install -y zip unzip
RUN apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install pdo_mysql

WORKDIR /var/www/html

# xdebug
RUN pecl install xdebug-3.0.2
RUN docker-php-ext-enable xdebug
COPY ./docker/usr/local/etc/php/php.ini /usr/local/etc/php/php.ini
