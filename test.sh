#!/usr/bin/env bash
set -e

PHP_VERSIONS=("8.5" "8.4" "8.3" "8.2" "8.1")

for PHP_VERSION in "${PHP_VERSIONS[@]}"; do

  sh dock "composer${PHP_VERSION}" composer update

  if ! sh dock "php${PHP_VERSION}" ./vendor/bin/phpunit
  then
    exit 1
  fi

  if [ "$PHP_VERSION" = "8.5" ]; then
    if ! sh dock "php${PHP_VERSION}" ./vendor/bin/phpunit --testsuite php85
    then
      exit 1
    fi
  fi
done