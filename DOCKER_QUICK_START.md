# Quick Docker Setup

Since building the Docker image is taking time due to network issues, here are two alternative approaches:

## Option 1: Use Laravel Sail (Easiest)

Laravel Sail provides pre-built Docker images. Let's use that:

### 1. Create sail helper script

Create a file `sail.ps1` in `C:\dashboard`:

```powershell
# sail.ps1
docker run --rm `
    -u "$(id -u):$(id -g)" `
    -v "${PWD}:/var/www/html" `
    -w /var/www/html `
    laravelsail/php82-composer:latest `
    composer install --ignore-platform-reqs
```

### 2. Run:
```powershell
cd C:\dashboard

# Pull Laravel Sail image
docker pull laravelsail/php82-composer:latest

# Install dependencies
docker run --rm -v ${PWD}:/app -w /app laravelsail/php82-composer:latest composer install --ignore-platform-reqs

# Start with Sail (if installed)
.\vendor\bin\sail up -d
```

---

## Option 2: Use Pre-built Image (Recommended)

Let me create a simpler docker-compose file using ready-made images:

Save this as `docker-compose-simple.yml`:

```yaml
services:
  mysql:
    image: mysql:8.0
    container_name: approval_db
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: content_approval
      MYSQL_USER: laravel
      MYSQL_PASSWORD: secret
    ports:
      - "3306:3306"
    volumes:
      - mysql_data:/var/lib/mysql
    healthcheck:
      test: ["CMD", "mysqladmin", "ping"]
      interval: 5s
      timeout: 5s
      retries: 10

  app:
    image: webdevops/php-nginx:8.2
    container_name: approval_app
    working_dir: /app
    volumes:
      - .:/app
    ports:
      - "8000:80"
    environment:
      - WEB_DOCUMENT_ROOT=/app/public
      - PHP_MEMORY_LIMIT=512M
      - PHP_MAX_EXECUTION_TIME=300
      - DB_HOST=mysql
      - DB_PORT=3306
      - DB_DATABASE=content_approval
      - DB_USERNAME=laravel
      - DB_PASSWORD=secret
    depends_on:
      mysql:
        condition: service_healthy

volumes:
  mysql_data:
```

Then run:
```powershell
# Pull images first
docker pull mysql:8.0
docker pull webdevops/php-nginx:8.2

# Start services
docker compose -f docker-compose-simple.yml up -d

# Wait for MySQL to be ready (30 seconds)
Start-Sleep -Seconds 30

# Run migrations
docker compose -f docker-compose-simple.yml exec app php artisan migrate --force
docker compose -f docker-compose-simple.yml exec app php artisan db:seed --force
```

---

## Option 3: Simple PHP + MySQL

Simplest possible setup:

```yaml
# docker-compose-minimal.yml
services:
  db:
    image: mysql:8
    environment:
      MYSQL_DATABASE: content_approval
      MYSQL_ROOT_PASSWORD: root
    ports:
      - "3306:3306"
  
  web:
    image: php:8.2-cli
    working_dir: /app
    volumes:
      - .:/app
    ports:
      - "8000:8000"
    command: php -S 0.0.0.0:8000 -t public
    depends_on:
      - db
    environment:
      DB_HOST: db
```

---

## Which Option?

- **Option 2 (webdevops)** - Most reliable, includes Nginx
- **Option 1 (Sail)** - Official Laravel solution  
- **Option 3 (Minimal)** - Simplest, but may have missing extensions

Try Option 2 first - it should work immediately!
