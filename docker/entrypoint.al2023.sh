#!/usr/bin/env bash
set -euo pipefail

# Ensure runtime and app directories exist and permissions are correct
mkdir -p /run/php-fpm || true
chown -R nginx:nginx /run/php-fpm || true
chown -R nginx:nginx /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache || true

# Bootstrap Laravel (safe/optional)
cd /var/www/html || exit 1

# Ensure composer autoload is optimized (vendor should exist from build)
if [ -f composer.json ]; then
  if [ -d vendor ]; then
    composer dump-autoload -o --no-interaction || true
  else
    composer install --no-dev --optimize-autoloader --no-interaction || true
  fi
fi

if [ -f artisan ]; then
  # Generate app key if missing
  if [ -z "${APP_KEY:-}" ] || [ "${APP_KEY}" = "" ]; then
    php artisan key:generate --force || true
  fi

  # Storage symlink
  php artisan storage:link || true

  # Caches (ignore failures in first boot)
  php artisan config:cache || true
  php artisan route:cache || true
  php artisan view:cache || true
  php artisan optimize || true

  # Run migrations if enabled
  if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force || echo "[warn] Migrations skipped or failed (DB may be unavailable)."
  fi
fi

# Configure PHP-FPM for AWS metadata access
# Allow PHP-FPM to access metadata service
echo "security.limit_extensions = .php" >> /etc/php-fpm.d/www.conf
echo "clear_env = no" >> /etc/php-fpm.d/www.conf

# Start php-fpm in background; keep stderr so it appears in docker logs
/usr/sbin/php-fpm -F &

# Nginx CORS map từ CORS_ALLOWED_ORIGINS (set bởi ECS/CDK theo từng môi trường)
if [ -x /usr/local/bin/generate-nginx-cors-map.sh ]; then
  /usr/local/bin/generate-nginx-cors-map.sh
fi

# Start nginx in foreground (PID 1 so container stays running)
exec /usr/sbin/nginx -g 'daemon off;'
