#!/bin/bash

echo "Starting project setup..."

# Check PHP version
echo "Checking PHP version..."
php_version=$(php -r "echo PHP_VERSION_ID;")
if [ "$php_version" -lt 80200 ]; then
    echo "Error: PHP 8.2 or higher is required. Current version: $(php -r 'echo PHP_VERSION;')"
    exit 1
fi

# Check required PHP extensions
echo "Checking required PHP extensions..."
required_extensions=("pdo" "pdo_mysql" "mbstring" "xml" "zip" "gd" "curl")
for ext in "${required_extensions[@]}"; do
    if ! php -m | grep -q "$ext"; then
        echo "Error: PHP extension '$ext' is not installed or enabled."
        exit 1
    fi
done

echo "Updating Composer dependencies..."
composer update

echo "Installing Composer dependencies..."
composer install

echo "Generating application key..."
php artisan key:generate

echo "Running database migrations and seeding..."
php artisan migrate:fresh --seed

# Install and build frontend assets
if [ -f package.json ]; then
    echo "Installing NPM dependencies..."
    npm install

    echo "Building frontend assets..."
    npm run build
fi

echo "Starting Laravel development server..."
php artisan serve 