#!/bin/bash

echo "🔄 Running post-pull Laravel commands..."

# Install/update composer dependencies (production mode)
echo "📦 Updating composer dependencies..."
composer install --no-dev --optimize-autoloader

# Run Laravel migrations
echo "🗄️  Running migrations..."
php artisan migrate --force

# Clear application cache
echo "🧹 Clearing caches..."
php artisan cache:clear

# Clear route cache
php artisan route:clear

# Clear config cache
php artisan config:clear

# Clear view cache
php artisan view:clear

# Rebuild config cache
echo "⚡ Rebuilding caches..."
php artisan config:cache

# Rebuild route cache
php artisan route:cache

# Rebuild view cache
php artisan view:cache

# Restart queue workers
echo "🔄 Restarting queue workers..."
php artisan queue:restart

echo "✅ Post-pull commands completed!"