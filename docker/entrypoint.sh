#!/usr/bin/env bash

# Exit on any failure
set -e

# If running as root, perform initial setup and switch to www user
if [ "$(id -u)" = "0" ]; then
    # Create log directories with proper permissions
    mkdir -p /var/log
    touch /var/log/php-fpm.log
    touch /var/log/php-fpm-access.log
    touch /var/log/php-fpm-slow.log
    touch /var/log/php-fpm-www-error.log
    chown -R www:www /var/log/php-fpm*.log
    chmod 666 /var/log/php-fpm*.log
    
    # Create PID directory with proper permissions
    mkdir -p /var/run
    touch /var/run/php-fpm.pid
    chown www:www /var/run/php-fpm.pid
    chmod 666 /var/run/php-fpm.pid
    
    # Change ownership of application files to www user
    chown -R www:www /var/www/html

    # Run php-fpm as root so master can write to /proc/self/fd/2 (workers stay www-data)
    exec "$@"
else
    # Already running as www user, execute directly
    exec "$@"
fi
