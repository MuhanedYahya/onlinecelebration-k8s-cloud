FROM composer as build 

WORKDIR /var/www/html

COPY src .

RUN composer install

FROM nginx:stable-alpine

COPY nginx/nginx.conf /etc/nginx/conf.d/default.conf

WORKDIR /var/www/html

COPY --from=build /var/www/html .

EXPOSE 443


