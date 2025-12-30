# Docker Quick Start Guide

### 1. Start the Application
```powershell
# Navigate to project directory
cd C:\dashboard

# Start all services (first time - will build images)
docker-compose up -d

# Wait for services to start (about 30-60 seconds)
```

### 2. Initialize Database
```powershell
# Run migrations (first time only)
docker-compose exec app php artisan migrate

# Seed the database with test data
docker-compose exec app php artisan db:seed
```

### 3. Access the Application
- API Base URL: http://localhost:8000/api
- API Documentation: http://localhost:8000

## Common Commands

### Start/Stop Services
```powershell
# Start services
docker-compose up -d

# Stop services
docker-compose down

# Restart services
docker-compose restart

# Stop and remove volumes (clean slate)
docker-compose down -v
```

### View Logs
```powershell
# View all logs
docker-compose logs

# Follow logs in real-time
docker-compose logs -f

# View specific service logs
docker-compose logs app
docker-compose logs mysql
```

### Run Artisan Commands
```powershell
# General format
docker-compose exec app php artisan [command]

# Examples:
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:list
docker-compose exec app php artisan tinker
```

### Access MySQL Database
```powershell
# Using docker exec
docker-compose exec mysql mysql -u laravel -psecret content_approval

# Or use database management tool:
# Host: localhost
# Port: 3306
# Database: content_approval
# Username: laravel
# Password: secret
```

### Composer Commands
```powershell
# Install packages
docker-compose exec app composer install

# Update packages
docker-compose exec app composer update

# Add new package
docker-compose exec app composer require vendor/package
```

### Shell Access
```powershell
# Access app container shell
docker-compose exec app bash

# Access MySQL shell
docker-compose exec mysql bash
```

## Test the API

### Using cURL in PowerShell
```powershell
# Login
$response = Invoke-RestMethod -Uri "http://localhost:8000/api/login" `
    -Method POST `
    -ContentType "application/json" `
    -Body '{"email":"admin@example.com","password":"password"}'

# Save token
$token = $response.token

# List posts
Invoke-RestMethod -Uri "http://localhost:8000/api/posts" `
    -Method GET `
    -Headers @{"Authorization"="Bearer $token"}
```

### Using Postman
1. Import `postman_collection.json`
2. Set `base_url` variable to `http://localhost:8000/api`
3. Start testing!

## Environment Configuration

The Docker setup uses these default settings:

**Application:**
- Port: 8000
- Environment: local
- Debug: true

**MySQL:**
- Host: mysql (internal), localhost (external)
- Port: 3306
- Database: content_approval
- User: laravel
- Password: secret
- Root Password: root

To change these, edit `docker-compose.yml` and restart:
```powershell
docker-compose down
docker-compose up -d
```

## Rebuilding Containers

If you change the Dockerfile or add new dependencies:

```powershell
# Rebuild and restart
docker-compose up -d --build

# Force complete rebuild
docker-compose build --no-cache
docker-compose up -d
```

## Troubleshooting

### Port Already in Use
```powershell
# Change port in docker-compose.yml
# Under app service, change:
ports:
  - "8080:8000"  # Use 8080 instead of 8000

# Restart
docker-compose down
docker-compose up -d
```

### Database Connection Issues
```powershell
# Check if MySQL is running
docker-compose ps

# Check MySQL logs
docker-compose logs mysql

# Restart MySQL
docker-compose restart mysql

# Wait 30 seconds, then retry migration
docker-compose exec app php artisan migrate
```

### Storage Permission Issues
```powershell
# Fix permissions
docker-compose exec app chmod -R 775 storage
docker-compose exec app chmod -R 775 bootstrap/cache
```

### Container Won't Start
```powershell
# View error logs
docker-compose logs app

# Remove and recreate
docker-compose down
docker-compose up -d --force-recreate
```

### Reset Everything
```powershell
# Stop and remove everything including volumes
docker-compose down -v

# Remove images
docker rmi dashboard_app

# Start fresh
docker-compose up -d --build

# Re-initialize
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

## Production Considerations

For production deployment:

1. **Update Dockerfile:**
   - Use `php:8.2-fpm` instead of `php:8.2-cli`
   - Add Nginx/Apache
   - Optimize with `--no-dev` flag

2. **Update docker-compose.yml:**
   - Remove port mappings for MySQL
   - Add environment-specific settings
   - Add health checks
   - Configure logging

3. **Security:**
   - Change default passwords
   - Use secrets management
   - Enable HTTPS
   - Restrict network access

## Useful Docker Commands

```powershell
# Check running containers
docker ps

# Check all containers
docker ps -a

# View container resource usage
docker stats

# Remove unused containers/images
docker system prune

# View container details
docker inspect content_approval_app

# Copy files to/from container
docker cp local-file.txt content_approval_app:/var/www/html/
docker cp content_approval_app:/var/www/html/file.txt ./
```

## Default Test Accounts

After seeding:
- Admin: admin@example.com / password
- Manager: manager@example.com / password
- Author: author@example.com / password

## Next Steps

1. Application is running at: http://localhost:8000
2. Test API endpoints using Postman
3. Check logs: `docker-compose logs -f`
4. Develop your application!

---

**Quick Reference:**
- Start: `docker-compose up -d`
- Stop: `docker-compose down`
- Logs: `docker-compose logs -f`
- Artisan: `docker-compose exec app php artisan [command]`
