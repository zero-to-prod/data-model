# run-tests.sh
set -e

php_versions=("php83" "php82" "php81")

for version in "${php_versions[@]}"; do
  echo "Setting up environment..."
  rm composer.lock
  docker compose run --rm "$version"composer composer install
  echo "Running tests on $version..."
  if ! docker compose run --rm "$version" vendor/bin/phpunit; then
    echo "Tests failed on $version."
    exit 1
  fi
done
echo "All tests passed!"