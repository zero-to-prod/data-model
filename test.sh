#!/bin/bash
set -e

php_versions=("php83" "php82" "php81")

for version in "${php_versions[@]}"; do
  vendor_dir="./vendor-${version}"

  if [ ! -d "$vendor_dir" ] || [ -z "$(ls -A "$vendor_dir")" ]; then
    echo "Dependencies not found or vendor directory is empty for $version. Installing dependencies..."
    docker compose run --rm "${version}composer" composer install --no-cache
  fi

  if ! docker compose run --rm "$version" vendor/bin/phpunit; then
    echo "Tests failed on $version."
    exit 1
  fi
done