#!/bin/bash

# Print PHP version
php -v

# Update composer
composer self-update

# Install dependencies with the correct PHP version
composer update

# Build assets
npm run build

# Create a .env file if it doesn't exist
if [ ! -f .env ]; then
  cp .env.example .env
  php artisan key:generate
fi

# Print success message
echo "Build completed successfully!"
