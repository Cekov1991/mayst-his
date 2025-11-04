#!/bin/bash
set -euo pipefail

# Ensure Docker CLI talks to Colima (works even if already set)
export DOCKER_HOST="unix://${HOME}/.colima/default/docker.sock"

echo "🔄 Restarting Docker containers..."

# Stop containers
echo "⏹️  Stopping containers..."
docker compose down

# Clear Laravel config cache
echo "🧹 Clearing Laravel caches..."
docker compose exec -T app php artisan config:clear 2>/dev/null || true
docker compose exec -T app php artisan cache:clear 2>/dev/null || true

# Start containers
echo "🚀 Starting containers..."
docker compose up -d

# Wait for MySQL to be ready
echo "⏳ Waiting for MySQL to be ready..."
until docker compose exec -T mysql mysqladmin ping -hmysql --silent 2>/dev/null; do
  sleep 1
done

# Clear config cache again after containers are up
echo "⚙️  Clearing configuration cache..."
docker compose exec -T app php artisan config:clear
docker compose exec -T app php artisan cache:clear

echo "✅ Containers restarted!"
echo ""
echo "🌐 Application: http://localhost:8080"
echo "📧 MailHog: http://localhost:8025"

