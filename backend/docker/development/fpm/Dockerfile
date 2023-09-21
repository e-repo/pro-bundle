FROM php:8.2-fpm-alpine

RUN apk add --no-cache autoconf build-base linux-headers \
    && pecl install xdebug-3.2.1 \
    && docker-php-ext-enable xdebug

RUN apk add --no-cache postgresql-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql

COPY ./conf.d/ $PHP_INI_DIR/conf.d/

WORKDIR /app