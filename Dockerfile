FROM php:8.0.2-fpm

COPY --from=composer:2.0.9 /usr/bin/composer /usr/bin/composer

RUN apt-get update
RUN apt-get install -y zip unzip
RUN docker-php-ext-install pdo_mysql

WORKDIR /var/www/html

# xdebug
RUN pecl install xdebug-3.0.2
RUN docker-php-ext-enable xdebug
COPY ./docker/usr/local/etc/php/php.ini /usr/local/etc/php/php.ini
