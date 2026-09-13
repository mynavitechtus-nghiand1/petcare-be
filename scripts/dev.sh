#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$ROOT_DIR"

cmd="${1:-help}"

ensure_env() {
  if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
  fi
}

wait_for_db() {
  echo "Waiting for Postgres..."
  for i in $(seq 1 30); do
    if docker compose exec -T database pg_isready -U laravel >/dev/null 2>&1; then
      echo "Postgres is ready."
      return 0
    fi
    sleep 2
  done
  echo "Postgres did not become ready in time."
  return 1
}

case "$cmd" in
  start)
    ensure_env
    echo "Building & starting containers..."
    docker compose up -d --build
    wait_for_db
    echo "Installing PHP dependencies (composer)..."
    docker compose exec -T app composer install --no-interaction
    if ! grep -q '^APP_KEY=base64:' .env 2>/dev/null; then
      echo "Generating APP_KEY..."
      docker compose exec -T app php artisan key:generate --force --no-interaction
    fi
    echo "Running migrations..."
    docker compose exec -T app php artisan migrate --force --no-interaction
    echo ""
    echo "Ready."
    echo "  API health:  http://localhost:${DOCKER_NGINX_PORT:-8080}/api/v1/health"
    echo "  Laravel up:  http://localhost:${DOCKER_NGINX_PORT:-8080}/up"
    echo "  MailHog:     http://localhost:${DOCKER_MAILHOG_WEB_UI_PORT:-8025}"
    ;;
  stop)
    docker compose down
    ;;
  restart)
    "$0" stop
    "$0" start
    ;;
  shell)
    docker compose exec app bash
    ;;
  logs)
    docker compose logs -f
    ;;
  migrate)
    docker compose exec -T app php artisan migrate --force --no-interaction
    ;;
  seed)
    docker compose exec -T app php artisan db:seed --force --no-interaction
    ;;
  test)
    docker compose exec -T app ./vendor/bin/pest --colors=always
    ;;
  help|*)
    echo "Usage: ./scripts/dev.sh {start|stop|restart|shell|logs|migrate|seed|test}"
    ;;
esac
