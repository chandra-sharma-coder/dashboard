# Manual Setup Guide for Windows

Since you don't have PHP installed and SSL certificate issues prevent Docker from installing dependencies, here are your options:

## Option 1: Run PowerShell as Administrator (Recommended)

1. **Right-click PowerShell** → Select "Run as Administrator"
2. Navigate to dashboard folder:
   ```powershell
   cd C:\dashboard
   ```
3. Run the installer:
   ```powershell
   Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass
   .\install-dependencies.ps1
   ```
4. After installation completes, restart Docker:
   ```powershell
   docker compose restart app
   ```

## Option 2: Manual PHP & Composer Installation

### Step 1: Install PHP
1. Download PHP 8.2 (Thread Safe) from: https://windows.php.net/download/
2. Extract to `C:\php`
3. Add to PATH: System Properties → Environment Variables → Add `C:\php` to PATH
4. Copy `php.ini-development` to `php.ini`
5. Enable extensions in php.ini:
   ```ini
   extension=pdo_mysql
   extension=mbstring
   extension=zip
   extension=openssl
   ```

### Step 2: Install Composer
1. Download from: https://getcomposer.org/Composer-Setup.exe
2. Run installer (it will detect PHP automatically)

### Step 3: Install Dependencies
```powershell
cd C:\dashboard
composer install
```

### Step 4: Restart Docker
```powershell
docker compose restart app
```

## Option 3: Use Pre-built Vendor (If you have access to another machine)

1. On a machine with working PHP/Composer:
   ```bash
   composer install
   zip -r vendor.zip vendor
   ```
2. Copy `vendor.zip` to `C:\dashboard`
3. Extract:
   ```powershell
   Expand-Archive -Path vendor.zip -DestinationPath C:\dashboard
   ```
4. Restart Docker:
   ```powershell
   docker compose restart app
   ```

## Option 4: WSL2 (Best for development)

1. Install WSL2:
   ```powershell
   wsl --install
   ```
2. Restart computer
3. In WSL terminal:
   ```bash
   cd /mnt/c/dashboard
   sudo apt update
   sudo apt install php php-cli php-mbstring php-xml php-zip unzip
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer
   composer install
   ```
4. Back in Windows PowerShell:
   ```powershell
   docker compose restart app
   ```

## After Dependencies Are Installed

Access your application:
- API: http://localhost:8080
- API Documentation: http://localhost:8080/api

Test endpoints:
```powershell
# Register a user
Invoke-RestMethod -Uri "http://localhost:8080/api/register" -Method POST -ContentType "application/json" -Body '{"name":"Admin User","email":"admin@example.com","password":"password123","password_confirmation":"password123","role":"admin"}'

# Login
Invoke-RestMethod -Uri "http://localhost:8080/api/login" -Method POST -ContentType "application/json" -Body '{"email":"admin@example.com","password":"password123"}'
```
