FROM php:8.1-cli AS builder

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    $PHPIZE_DEPS \
  && rm -rf /var/lib/apt/lists/*

RUN git config --global --add safe.directory /app

WORKDIR /app

FROM builder AS composer

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer \
  && chmod +x /usr/local/bin/composer

FROM builder AS debug

RUN pecl channel-update pecl.php.net \
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && apt-get purge -y --auto-remove $PHPIZE_DEPS \
  && rm -rf /tmp/* /var/lib/apt/lists/*

FROM php:8.1-cli AS base

WORKDIR /app

CMD ["bash"]