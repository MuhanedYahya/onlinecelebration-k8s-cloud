FROM composer as build 

WORKDIR /var/www/html

COPY src .

RUN composer install

RUN composer require promphp/prometheus_client_php

FROM nginx:stable-alpine

COPY nginx/nginx.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/html

COPY --from=build /var/www/html .

EXPOSE 80


