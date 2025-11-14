FROM composer:2.7 as builder_vendor

WORKDIR /app

COPY composer.json ./

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

COPY . .

FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
    nginx \
    curl \
    curl-dev

RUN docker-php-ext-install curl

COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

COPY --from=builder_vendor /app .

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

CMD ["php-fpm"]
