FROM php:7.4-fpm

RUN apt-get update

# xdebug
RUN pecl install xdebug-3.0.2
RUN docker-php-ext-enable xdebug
COPY ./docker/usr/local/etc/php/php.ini /usr/local/etc/php/php.ini

# 日本語のロケールに変更
RUN apt-get install -y apt-utils locales
RUN echo "ja_JP.UTF-8 UTF-8" > /etc/locale.gen
RUN locale-gen ja_JP.UTF-8
ENV LC_ALL ja_JP.UTF-8

RUN apt-get install -y git zip unzip vim
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql

WORKDIR /var/www/html
