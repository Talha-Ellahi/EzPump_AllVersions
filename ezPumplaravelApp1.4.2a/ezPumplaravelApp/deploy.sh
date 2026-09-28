#!/bin/bash

# ezPump Laravel App Deployment Script
# This script pulls latest changes and runs necessary Laravel commands

echo "🚀 Starting deployment for ezPump Laravel App..."

# Check if we're in a git repository
if [ ! -d ".git" ]; then
    echo "❌ Error: Not in a git repository!"
    exit 1
fi

# Pull latest changes from git
echo "📥 Pulling latest changes from git..."
git pull

# Check if git pull was successful
if [ $? -ne 0 ]; then
    echo "❌ Git pull failed! Aborting deployment."
    exit 1
fi

echo "✅ Git pull successful!"

# Install/update composer dependencies
echo "📦 Installing/updating composer dependencies..."
composer install --no-dev --optimize-autoloader

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Clear all caches
echo "🧹 Clearing application caches..."
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear

# Rebuild caches for production
echo "⚡ Rebuilding caches for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear and rebuild application cache
echo "🔄 Optimizing application..."
php artisan optimize

# Set proper permissions for storage and bootstrap/cache
echo "🔐 Setting proper permissions..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Restart queue workers if they exist
echo "🔄 Restarting queue workers..."
php artisan queue:restart

echo "🎉 Deployment completed successfully!"
echo "📋 Summary of actions performed:"
echo "   ✅ Git pull"
echo "   ✅ Composer install"
echo "   ✅ Database migrations"
echo "   ✅ Cache clearing and rebuilding"
echo "   ✅ Permissions set"
echo "   ✅ Queue workers restarted"
