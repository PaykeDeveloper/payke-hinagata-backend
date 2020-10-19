FROM php:7.4-fpm

RUN apt-get update

# 日本語のロケールに変更
RUN apt-get install -y apt-utils locales
RUN echo "ja_JP.UTF-8 UTF-8" > /etc/locale.gen
RUN locale-gen ja_JP.UTF-8
ENV LC_ALL ja_JP.UTF-8

# composer install
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

RUN apt-get install -y git zip unzip vim
RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql
# queue:workerの実行永続のためプロセスモニタを追加
RUN apt-get install -y supervisor
WORKDIR /var/www/html