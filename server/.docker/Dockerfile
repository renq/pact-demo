# BASE image
FROM php:8-fpm-buster as base
WORKDIR /var/www/app

# Composer
FROM composer:2 as composer

# DEV image
FROM base as dev

ARG APP_USER_ID
ARG APP_GROUP_ID

RUN usermod -u 1000 www-data

RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN usermod -u $APP_USER_ID www-data --shell /bin/bash
RUN groupmod -g $APP_GROUP_ID www-data

# Build image (used for production image)
FROM base as build

RUN apt install zip libzip-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY --chown=www-data:www-data composer.json composer.lock /var/www/app/

RUN chown www-data:www-data /var/www/ -R
USER www-data:www-data

RUN composer install --prefer-dist --no-scripts --no-interaction --no-progress --no-dev  --no-cache --optimize-autoloader

# main image

FROM base
COPY --chown=www-data:www-data . /var/www/app/
COPY --from=build --chown=www-data:www-data /var/www/app/vendor /var/www/app/vendor

