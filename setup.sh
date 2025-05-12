#!/bin/bash

echo "Starting project setup..."

# Create .env from .env.example if it doesn't exist
if [ ! -f .env ]; then
    echo ".env file not found. Creating from .env.example..."
    cp .env.example .env
    # Set DB and Calendarific values
    sed -i 's/^DB_CONNECTION=.*/DB_CONNECTION=mysql/' .env
    sed -i 's/^DB_HOST=.*/DB_HOST=127.0.0.1/' .env
    sed -i 's/^DB_PORT=.*/DB_PORT=3306/' .env
    sed -i 's/^DB_DATABASE=.*/DB_DATABASE=presence_db/' .env
    sed -i 's/^DB_USERNAME=.*/DB_USERNAME=root/' .env
    sed -i 's/^DB_PASSWORD=.*/DB_PASSWORD=/' .env
    # Add Calendarific API key if not present
    if ! grep -q '^CALENDARIFIC_API_KEY=' .env; then
        echo 'CALENDARIFIC_API_KEY=DdWErgLjhp0rQuirOwOGMGSQ5oF6Xgiv' >> .env
    else
        sed -i 's/^CALENDARIFIC_API_KEY=.*/CALENDARIFIC_API_KEY=DdWErgLjhp0rQuirOwOGMGSQ5oF6Xgiv/' .env
    fi
fi

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