FROM php:8.1-fpm

RUN apt-get update

# xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
COPY ./docker/usr/local/etc/php/php.ini /usr/local/etc/php/php.ini

# composer
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer
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

# laravel-lang/publisher
RUN docker-php-ext-install bcmath

WORKDIR /var/www/html
