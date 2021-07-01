FROM php:8.0.3-fpm

RUN apt-get update

# xdebug
RUN pecl install xdebug-3.0.4
RUN docker-php-ext-enable xdebug
COPY ./docker/usr/local/etc/php/php.ini /usr/local/etc/php/php.ini

# handle non-POST Form Data.
RUN pecl install apfd

# composer
COPY --from=composer:2.1.3 /usr/bin/composer /usr/bin/composer
RUN apt-get install -y unzip

# mysql
RUN docker-php-ext-install pdo_mysql

# spatie/laravel-medialibrary
RUN apt-get install -y zlib1g-dev libpng-dev
RUN docker-php-ext-install gd
RUN docker-php-ext-install exif

# maatwebsite/excel
RUN apt-get install -y libzip-dev
RUN docker-php-ext-install zip

WORKDIR /var/www/html
