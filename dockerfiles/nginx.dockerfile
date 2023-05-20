FROM composer as build 

WORKDIR /var/www/html

COPY src .
# install vendor with composer
RUN composer update

# require laravel metric exporter
RUN composer require promphp/prometheus_client_php

FROM nginx:stable-alpine

COPY nginx/nginx.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/html

COPY --from=build /var/www/html .

EXPOSE 80


