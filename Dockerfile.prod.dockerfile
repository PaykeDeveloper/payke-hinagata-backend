# Use the official PHP image.
# https://hub.docker.com/_/php
FROM php:8.2-apache

# Configure PHP for Cloud Run.
# Precompile PHP code with opcache.
RUN docker-php-ext-install -j "$(nproc)" opcache
RUN set -ex; \
  { \
    echo "; Cloud Run enforces memory & timeouts"; \
    echo "memory_limit = -1"; \
    echo "max_execution_time = 0"; \
    echo "; File upload at Cloud Run network limit"; \
    echo "upload_max_filesize = 32M"; \
    echo "post_max_size = 32M"; \
    echo "; Configure Opcache for Containers"; \
    echo "opcache.enable = On"; \
    echo "opcache.validate_timestamps = Off"; \
    echo "; Configure Opcache Memory (Application-specific)"; \
    echo "opcache.memory_consumption = 32"; \
  } > "$PHP_INI_DIR/conf.d/cloud-run.ini"

# Copy in custom code from the host machine.
WORKDIR /var/www/html
#COPY . ./

# Use the PORT environment variable in Apache configuration files.
# https://cloud.google.com/run/docs/reference/container-contract#port
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Configure PHP for development.
# Switch to the production php.ini for production operations.
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
# https://github.com/docker-library/docs/blob/master/php/README.md#configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"


ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite


RUN apt-get update

# composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer
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

ARG user=www-data
ARG group=$user

ENV HOME /home/$user
RUN mkdir $HOME
RUN chown -R $user:$group $HOME

COPY . .

RUN chown -R $user:$group .
USER $user

ENV APP_ENV=production
RUN ln -s /secrets/env .env.production

RUN composer install --no-dev --optimize-autoloader
RUN composer optimize
