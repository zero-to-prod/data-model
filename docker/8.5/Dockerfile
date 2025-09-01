FROM php:8.5-rc-alpine AS builder

RUN apk add --no-cache \
    git \
    unzip \
    $PHPIZE_DEPS

RUN git config --global --add safe.directory /app

WORKDIR /app

FROM builder AS composer

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

FROM builder AS debug

RUN apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    linux-headers \
  && pecl channel-update pecl.php.net \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && apk del .build-deps \
  && rm -rf /tmp/* /var/cache/apk/*

FROM php:8.5-rc-alpine AS base

WORKDIR /app

CMD ["bash"]