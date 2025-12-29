# SSL Certificate Issue - Solutions

Your Docker environment has SSL certificate issues (corporate proxy or antivirus intercepting HTTPS traffic).

## Quick Solutions

### Option 1: Temporarily Disable Antivirus/Firewall SSL Inspection
1. Temporarily disable SSL inspection in your antivirus (Norton, Kaspersky, Avast, etc.)
2. Run: `docker compose down && docker compose up -d --build`
3. Re-enable antivirus after composer install completes

### Option 2: Use Pre-built Laravel Docker Image
Instead of building from scratch, use a pre-made image:

```bash
# Stop current containers
docker compose down

# Pull official Laravel image
docker pull bitnami/laravel:latest
```

### Option 3: Install on Windows Directly (Recommended)
1. Install PHP and Composer on Windows
2. Run composer install locally
3. Use Docker only for MySQL

**Install PHP on Windows:**
```powershell
# Using Chocolatey
choco install php composer -y

# Or download from:
# PHP: https://windows.php.net/download/
# Composer: https://getcomposer.org/download/
```

**Then run:**
```powershell
cd C:\dashboard
composer install
php artisan serve
```

### Option 4: Use WSL2 Instead of Docker Desktop
Windows Subsystem for Linux often has fewer SSL issues:

```powershell
wsl --install
wsl
# Then inside WSL:
cd /mnt/c/dashboard
composer install
php artisan serve
```

### Option 5: Manual Vendor Download
If you have access to another machine without SSL issues:
1. Run `composer install` there
2. Zip the `vendor` folder
3. Copy to your Windows machine
4. Extract to C:\dashboard\vendor

## Testing Your Solution

Once vendor folder exists:
```powershell
docker compose up -d
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed
```

Then visit: http://localhost:8080
