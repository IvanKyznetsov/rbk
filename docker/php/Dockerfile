FROM composer:2.0 as composer

FROM php:7.4-fpm

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y zlib1g-dev git zip unzip libpq-dev

RUN docker-php-ext-install pdo_pgsql

WORKDIR /var/www
