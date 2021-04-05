FROM php:8.0.3-fpm

RUN apt-get update

# xdebug
RUN pecl install xdebug-3.0.3
RUN docker-php-ext-enable xdebug
COPY ./docker/usr/local/etc/php/php.ini /usr/local/etc/php/php.ini

# composer
COPY --from=composer:2.0.12 /usr/bin/composer /usr/bin/composer
RUN apt-get install -y zip unzip

# mysql
RUN docker-php-ext-install pdo_mysql

# spatie/laravel-medialibrary
RUN apt-get install -y zlib1g-dev libpng-dev
RUN docker-php-ext-install gd
RUN docker-php-ext-install exif


WORKDIR /var/www/html
