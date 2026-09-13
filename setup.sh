#!/bin/bash

# Laravel 12 Setup Script
echo "🚀 Setting up Laravel 12 with PHP 8.4..."

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker is not running. Please start Docker first."
    exit 1
fi

# Create .env from example if it doesn't exist
if [ ! -f .env ]; then
    echo "📋 Creating .env file..."
    cp .env.example .env
fi

# Build and start containers
echo "🏗️  Building Docker containers..."
docker-compose up -d --build

# Wait for database to be ready
echo "⏳ Waiting for database to be ready..."
sleep 10

# Check if Laravel is already installed
if [ ! -f composer.json ]; then
    echo "📦 Installing Laravel 12..."
    
    # Install Laravel in a temporary directory and move files
    docker-compose exec -T app composer create-project laravel/laravel:^12.0 temp --prefer-dist --no-interaction
    docker-compose exec -T app bash -c "
        mv temp/* . 2>/dev/null || true
        mv temp/.* . 2>/dev/null || true
        rmdir temp 2>/dev/null || true
        chown -R www:www /var/www/html
        chmod -R 775 storage bootstrap/cache
    "
    
    echo "🔑 Generating application key..."
    docker-compose exec -T app php artisan key:generate --no-interaction
    
    echo "🗄️  Running database migrations..."
    docker-compose exec -T app php artisan migrate --no-interaction
    
    echo "🔗 Creating storage symlink..."
    docker-compose exec -T app php artisan storage:link --no-interaction
else
    echo "✅ Laravel is already installed."
fi

echo ""
echo "🎉 Setup completed successfully!"
echo ""
echo "📍 Services:"
echo "   🌐 Application: http://localhost:8080"
echo "   📧 MailHog:     http://localhost:8025"
echo "   🗄️  MinIO:       http://localhost:9001"
echo ""
echo "🔧 Useful commands:"
echo "   docker-compose exec app bash              # Enter app container"
echo "   docker-compose exec app php artisan       # Run Artisan commands"
echo "   docker-compose logs -f                    # View logs"
echo "   docker-compose down                       # Stop containers"
echo ""
