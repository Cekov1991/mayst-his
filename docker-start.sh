#!/bin/bash
set -euo pipefail

echo "🐳 Starting Mayst HIS Docker Environment..."

# Ensure Docker CLI talks to Colima (works even if already set)
export DOCKER_HOST="unix://${HOME}/.colima/default/docker.sock"

# Copy .env.example if .env doesn't exist
if [ ! -f .env ]; then
  echo "📝 Creating .env file from .env.example..."
  cp .env.example .env
fi

# Build and start containers (Compose v2)
echo "🔨 Building and starting Docker containers..."
docker compose up -d --build

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
until docker compose exec -T mysql mysqladmin ping -hmysql --silent; do
  sleep 1
done

# Generate app key if not set
if ! grep -q "^APP_KEY=base64:" .env; then
  echo "🔑 Generating application key..."
  docker compose exec -T app php artisan key:generate
fi

# Clear and cache config
echo "⚙️  Clearing and caching configuration..."
docker compose exec -T app php artisan config:clear
docker compose exec -T app php artisan cache:clear

# Run migrations
echo "📊 Running database migrations..."
docker compose exec -T app php artisan migrate --force

# Install and build assets
echo "🎨 Building frontend assets..."
docker compose exec -T app npm install
docker compose exec -T app npm run build

echo "✅ Environment ready!"
echo ""
echo "🌐 Application: http://localhost:8080"
echo "📧 MailHog: http://localhost:8025"
echo "🗄️  MySQL: localhost:3306"
echo "🔴 Redis: localhost:6379"
echo ""
echo "To stop: docker compose down"
echo "To view logs: docker compose logs -f"
