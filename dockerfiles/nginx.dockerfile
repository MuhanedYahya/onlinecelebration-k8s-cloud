FROM composer as build 

WORKDIR /var/www/html

COPY src .

RUN composer install

FROM nginx:stable-alpine

RUN mkdir /etc/nginx/certs
# nginx conf
COPY nginx/nginx.conf /etc/nginx/conf.d/default.conf
# ssl certs
COPY nginx/server.pem /etc/nginx/certs/server.pem
COPY nginx/intermediate.pem /etc/nginx/certs/intermediate.pem
COPY nginx/root.pem /etc/nginx/certs/root.pem
COPY nginx/piplineapp.live.key /etc/nginx/certs/piplineapp.live.key

WORKDIR /var/www/html

COPY --from=build /var/www/html .

EXPOSE 443


