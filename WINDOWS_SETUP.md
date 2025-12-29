# Windows Setup Guide

## Prerequisites Installation for Windows

Since you don't have PHP installed yet, here are your options to run this Laravel application on Windows:

---

## Option 1: Using Laragon (Recommended for Beginners)

**Laragon** is an all-in-one local development environment for Windows that includes PHP, MySQL, and more.

### Installation Steps:

1. **Download Laragon**
   - Visit: https://laragon.org/download/
   - Download "Laragon Full" (includes PHP, MySQL, Apache)
   - File size: ~100MB

2. **Install Laragon**
   ```
   - Run the installer
   - Choose installation directory (default: C:\laragon)
   - Complete installation
   ```

3. **Start Laragon**
   ```
   - Open Laragon
   - Click "Start All" button
   - Apache and MySQL will start automatically
   ```

4. **Set Up Project**
   ```powershell
   # Open Laragon Terminal (right-click Laragon icon → Terminal)
   cd C:\laragon\www
   
   # Copy your dashboard project here or create symlink
   mklink /D content-approval C:\dashboard
   
   # Navigate to project
   cd content-approval
   
   # Install dependencies
   composer install
   
   # Set up environment
   copy .env.example .env
   php artisan key:generate
   
   # Configure database in .env (Laragon defaults):
   # DB_HOST=127.0.0.1
   # DB_PORT=3306
   # DB_DATABASE=content_approval
   # DB_USERNAME=root
   # DB_PASSWORD=
   
   # Create database (in Laragon Terminal)
   mysql -u root -e "CREATE DATABASE content_approval"
   
   # Run migrations
   php artisan migrate
   php artisan db:seed
   
   # Start development server
   php artisan serve
   ```

5. **Access Application**
   - Open browser: http://localhost:8000
   - API: http://localhost:8000/api

---

## Option 2: Using Docker Desktop (Recommended for Production-like Environment)

**Docker** provides a containerized environment that works the same across all systems.

### Installation Steps:

1. **Install Docker Desktop**
   - Visit: https://www.docker.com/products/docker-desktop
   - Download Docker Desktop for Windows
   - Install and restart your computer

2. **Create Docker Files**
   
   Create `docker-compose.yml` in your project root:
   ```yaml
   version: '3.8'
   
   services:
     app:
       build:
         context: .
         dockerfile: Dockerfile
       ports:
         - "8000:8000"
       volumes:
         - .:/var/www/html
       depends_on:
         - mysql
       environment:
         - DB_HOST=mysql
         - DB_DATABASE=content_approval
         - DB_USERNAME=laravel
         - DB_PASSWORD=secret
   
     mysql:
       image: mysql:8.0
       ports:
         - "3306:3306"
       environment:
         - MYSQL_ROOT_PASSWORD=root
         - MYSQL_DATABASE=content_approval
         - MYSQL_USER=laravel
         - MYSQL_PASSWORD=secret
       volumes:
         - mysql_data:/var/lib/mysql
   
   volumes:
     mysql_data:
   ```

3. **Create Dockerfile** in project root:
   ```dockerfile
   FROM php:8.2-cli
   
   WORKDIR /var/www/html
   
   RUN apt-get update && apt-get install -y \
       git \
       curl \
       zip \
       unzip \
       libonig-mbstring-dev \
       libzip-dev
   
   RUN docker-php-ext-install pdo_mysql mbstring zip
   
   COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
   
   COPY . .
   
   RUN composer install
   
   CMD php artisan serve --host=0.0.0.0 --port=8000
   ```

4. **Run with Docker**
   ```powershell
   # In your project directory (C:\dashboard)
   docker-compose up -d
   
   # Run migrations (first time only)
   docker-compose exec app php artisan migrate
   docker-compose exec app php artisan db:seed
   
   # Access application
   # Open browser: http://localhost:8000
   ```

5. **Docker Commands**
   ```powershell
   # Start services
   docker-compose up -d
   
   # Stop services
   docker-compose down
   
   # View logs
   docker-compose logs -f
   
   # Run artisan commands
   docker-compose exec app php artisan [command]
   ```

---

## Option 3: Manual PHP Installation

### Step 1: Install PHP

1. **Download PHP**
   - Visit: https://windows.php.net/download/
   - Download "VS16 x64 Thread Safe" ZIP (PHP 8.2+)
   - Example: `php-8.2.x-Win32-vs16-x64.zip`

2. **Extract PHP**
   ```
   - Extract to: C:\php
   - You should have: C:\php\php.exe
   ```

3. **Add PHP to System PATH**
   ```
   - Press Windows + X → System
   - Click "Advanced system settings"
   - Click "Environment Variables"
   - Under "System variables", find "Path"
   - Click "Edit" → "New"
   - Add: C:\php
   - Click OK on all dialogs
   - Restart PowerShell
   ```

4. **Configure PHP**
   ```powershell
   # In C:\php directory
   copy php.ini-development php.ini
   
   # Open php.ini in notepad and enable these extensions:
   # Remove semicolon (;) from start of these lines:
   extension=mbstring
   extension=openssl
   extension=pdo_mysql
   extension=curl
   extension=fileinfo
   extension=zip
   ```

### Step 2: Install Composer

1. **Download Composer**
   - Visit: https://getcomposer.org/download/
   - Download "Composer-Setup.exe"
   - Run installer (it will detect your PHP installation)

2. **Verify Installation**
   ```powershell
   composer --version
   ```

### Step 3: Install MySQL

1. **Download MySQL**
   - Visit: https://dev.mysql.com/downloads/installer/
   - Download "MySQL Installer for Windows"
   - Choose "Custom" installation
   - Select: MySQL Server 8.0

2. **Configure MySQL**
   ```
   - Set root password (remember this!)
   - Choose "Use Legacy Authentication"
   - Complete installation
   ```

3. **Create Database**
   ```powershell
   # Open MySQL Command Line Client
   # Enter your root password
   
   CREATE DATABASE content_approval;
   exit;
   ```

### Step 4: Set Up Laravel Project

```powershell
# Navigate to project
cd C:\dashboard

# Install dependencies
composer install

# Copy environment file
copy .env.example .env

# Generate application key
php artisan key:generate

# Edit .env file with your database credentials
notepad .env

# Update these lines in .env:
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=content_approval
# DB_USERNAME=root
# DB_PASSWORD=your_mysql_password

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Start development server
php artisan serve
```

---

## Option 4: Using XAMPP

**XAMPP** is another popular all-in-one package for Windows.

### Installation Steps:

1. **Download XAMPP**
   - Visit: https://www.apachefriends.org/
   - Download latest version with PHP 8.2+
   - Install to default location

2. **Start Services**
   ```
   - Open XAMPP Control Panel
   - Start "Apache"
   - Start "MySQL"
   ```

3. **Install Composer**
   - Follow Step 2 from Option 3 above

4. **Set Up Project**
   ```powershell
   # Navigate to project
   cd C:\dashboard
   
   # Install dependencies
   C:\xampp\php\php.exe C:\composer\composer.phar install
   
   # Or if composer is in PATH:
   composer install
   
   # Continue with setup
   copy .env.example .env
   php artisan key:generate
   
   # Create database using phpMyAdmin
   # Open: http://localhost/phpmyadmin
   # Create database: content_approval
   
   # Update .env with database settings
   # Run migrations
   php artisan migrate
   php artisan db:seed
   
   # Start server
   php artisan serve
   ```

---

## Verification Steps

After installation, verify everything works:

```powershell
# Check PHP version (should be 8.2+)
php -v

# Check Composer
composer --version

# Check MySQL connection
php artisan migrate:status

# Test the application
php artisan serve
```

Then open browser to: http://localhost:8000

---

## Quick Start After Installation

```powershell
# 1. Start development server
php artisan serve

# 2. Test API
# Login as admin
curl -X POST http://localhost:8000/api/login `
  -H "Content-Type: application/json" `
  -d '{\"email\":\"admin@example.com\",\"password\":\"password\"}'

# 3. Use Postman
# Import postman_collection.json for easy testing
```

---

## Recommended Tools

### Code Editor
- **Visual Studio Code**: https://code.visualstudio.com/
- Install extensions:
  - PHP Intelephense
  - Laravel Extension Pack
  - REST Client

### Database Management
- **HeidiSQL**: https://www.heidisql.com/ (Free)
- **TablePlus**: https://tableplus.com/ (Free tier available)

### API Testing
- **Postman**: https://www.postman.com/downloads/
- **Insomnia**: https://insomnia.rest/download

---

## Troubleshooting

### "php is not recognized"
- PHP not in PATH
- Solution: Add PHP directory to PATH (see Option 3, Step 3)

### "composer is not recognized"
- Composer not installed or not in PATH
- Solution: Install Composer from getcomposer.org

### "SQLSTATE[HY000] [2002] Connection refused"
- MySQL not running
- Solution: Start MySQL service

### "Class not found" errors
- Autoload not updated
- Solution: `composer dump-autoload`

### Port 8000 already in use
- Another service using the port
- Solution: Use different port
  ```powershell
  php artisan serve --port=8080
  ```

---

## Next Steps

Once everything is running:

1. Read [API_DOCUMENTATION.md](API_DOCUMENTATION.md) for API usage
2. Import [postman_collection.json](postman_collection.json) to Postman
3. Test with default accounts (see README.md)
4. Start building your frontend!

---

## Support

If you continue to have issues:
1. Check Laravel documentation: https://laravel.com/docs
2. Verify PHP version: `php -v` (must be 8.2+)
3. Check error logs: `storage/logs/laravel.log`
4. Ensure all extensions are enabled in php.ini

---

**Recommended for Quick Start:** Option 1 (Laragon) - Everything in one package!

**Recommended for Learning:** Option 3 (Manual) - Understand each component

**Recommended for Teams:** Option 2 (Docker) - Consistent across all machines
