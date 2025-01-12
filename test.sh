#!/usr/bin/env bash
set -e

PHP_VERSIONS=("8.4" "8.3" "8.2" "8.1" "8.0" "7.4" "7.3" "7.2" "7.1")

for PHP_VERSION in "${PHP_VERSIONS[@]}"; do

  sh dock "composer${PHP_VERSION}" composer update

  if ! sh dock "php${PHP_VERSION}" ./vendor/bin/phpunit
  then
    exit 1
  fi
done