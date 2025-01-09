#!/usr/bin/env bash
set -e

PHP_VERSIONS=("8.4" "8.3" "8.2" "8.1")

for version in "${PHP_VERSIONS[@]}"; do

  docker compose run --rm "composer${version}" composer update

  if ! docker compose run --rm "php${version}" ./vendor/bin/phpunit
  then
    exit 1
  fi
done