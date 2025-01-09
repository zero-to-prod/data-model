FROM php:7.1-cli AS builder

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    $PHPIZE_DEPS \
  && rm -rf /var/lib/apt/lists/*

WORKDIR /app

FROM builder AS composer

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

FROM builder AS debug

RUN pecl channel-update pecl.php.net \
  && pecl install xdebug-2.9.8 \
  && docker-php-ext-enable xdebug

FROM php:7.1-cli AS base

WORKDIR /app

CMD ["bash"]