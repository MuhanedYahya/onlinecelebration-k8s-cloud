FROM composer as build 

WORKDIR /var/www/html

COPY src .
# install vendor with composer
RUN composer update

# require laravel metric exporter
RUN composer require promphp/prometheus_client_php

# alpine not recommended
FROM php:8.1-fpm

WORKDIR /var/www/html
# mysql driver
RUN docker-php-ext-install pdo pdo_mysql

COPY --from=build /var/www/html .
# install redis extension

RUN pecl install redis && docker-php-ext-enable redis

RUN chown -R www-data:www-data /var/www/html