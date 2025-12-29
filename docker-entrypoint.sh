#!/bin/sh
set -e

echo "=== Laravel Application Startup ==="

# Install composer dependencies if vendor doesn't exist
if [ ! -d "vendor" ]; then
    echo "WARNING: vendor directory not found!"
    echo "Attempting to install composer dependencies..."
    export COMPOSER_ALLOW_SUPERUSER=1
    export COMPOSER_NO_INTERACTION=1
    
    # Try composer install with SSL disabled
    export COMPOSER_DISABLE_TLS=1
    composer config -g -- disable-tls true 2>/dev/null || true
    composer config -g -- secure-http false 2>/dev/null || true
    
    echo "Running composer install (this may fail due to SSL issues)..."
    composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts 2>&1 || {
        echo ""
        echo "=========================================="
        echo "COMPOSER INSTALL FAILED!"
        echo "=========================================="
        echo "Your network has SSL certificate issues."
        echo ""
        echo "To fix this, run composer install on your host machine:"
        echo "1. Install PHP and Composer on Windows"
        echo "2. Run: composer install"
        echo "3. Restart containers: docker compose restart app"
        echo ""
        echo "Or see SSL_ISSUE_SOLUTION.md for alternatives."
        echo "=========================================="
        echo ""
        echo "Container will keep trying to start..."
        sleep 10
        exit 1
    }
else
    echo "Composer dependencies already installed ✓"
fi

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
until php artisan db:show 2>/dev/null; do
  echo "MySQL is unavailable - sleeping"
  sleep 2
done

echo "MySQL is up and ready! ✓"

# Run migrations if needed
echo "Running migrations..."
php artisan migrate --force || true

echo ""
echo "=========================================="
echo "Laravel Application Started Successfully!"
echo "=========================================="
echo "Access the API at: http://localhost:8080"
echo "=========================================="
echo ""

exec php artisan serve --host=0.0.0.0 --port=8000
