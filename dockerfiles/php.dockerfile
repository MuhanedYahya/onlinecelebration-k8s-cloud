FROM composer as build 

WORKDIR /var/www/html

COPY src .
RUN composer require promphp/prometheus_client_php
RUN composer install

FROM php:8.1-fpm-alpine
 
WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=build /var/www/html .

RUN chown -R www-data:www-data /var/www/html
