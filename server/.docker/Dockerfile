# BASE image
FROM php:8-fpm as base

# Composer
FROM composer:2 as composer

# DEV image
FROM base as dev

ARG APP_USER_ID
ARG APP_GROUP_ID

WORKDIR /var/www/app
COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN usermod -u $APP_USER_ID www-data --shell /bin/bash
RUN groupmod -g $APP_GROUP_ID www-data

FROM base
COPY . .
