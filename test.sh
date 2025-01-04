#!/bin/bash
set -e

php_versions=("php83" "php82" "php81")

for version in "${php_versions[@]}"; do
  docker compose run --rm "${version}composer" composer update --no-cache

  if ! docker compose run --rm "$version" vendor/bin/phpunit; then
    exit 1
  fi
done