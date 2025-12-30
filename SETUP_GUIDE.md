# Content Approval System - Setup Guide

## Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL/MariaDB
- (Optional) Postman for API testing

### Installation Steps

1. **Install Composer dependencies** (when Composer is available):
```bash
composer install
```

2. **Configure environment**:
   - Copy `.env.example` to `.env`
   - Update database credentials in `.env`
   - Generate application key:
   ```bash
   php artisan key:generate
   ```

3. **Create database**:
```sql
CREATE DATABASE content_approval;
```

4. **Run migrations**:
```bash
php artisan migrate
```

5. **Seed the database**:
```bash
php artisan db:seed
```

6. **Start the development server**:
```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

---

## Default Test Accounts

After seeding, you can use these accounts:

| Role    | Email                  | Password |
|---------|------------------------|----------|
| Admin   | admin@example.com      | password |
| Manager | manager@example.com    | password |
| Author  | author@example.com     | password |

---

## API Testing

### Using cURL

1. **Login**:
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "author@example.com", "password": "password"}'
```

Save the token from the response.

2. **Create a post**:
```bash
curl -X POST http://localhost:8000/api/posts \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{"title": "My Post", "body": "Post content here"}'
```

### Using Postman

1. Import the `postman_collection.json` file
2. Update the `base_url` variable if needed
3. Run the "Login" request and copy the token
4. Update the `token` variable with your token
5. Test other endpoints

## Key Features

### 1. Role-Based Access Control
- **Author**: Create and update own posts
- **Manager**: Approve/reject posts
- **Admin**: All permissions + delete posts

### 2. Post Workflow
- Posts start as "pending"
- Managers/Admins approve or reject
- Authors can only modify their own posts
- Complete audit trail via activity logs

### 3. Activity Logging
Every action is logged:
- Post created
- Post updated
- Post approved
- Post rejected
- Post deleted

### 4. Clean Architecture
- Policy-based authorization
- Service layer for business logic
- Enum classes for type safety
- Repository pattern via Eloquent
- Middleware for role checking

---

## Testing the Workflow

### As Author (author@example.com):
1. Login → Get token
2. Create post → Status: pending
3. View own posts only
4. Update own posts

### As Manager (manager@example.com):
1. Login → Get token
2. View all posts
3. Approve pending posts
4. Reject posts with reason
5. Cannot delete posts

### As Admin (admin@example.com):
1. Login → Get token
2. View all posts
3. Approve/reject posts
4. Delete any post
5. Full access to everything

---

## API Endpoints Summary

### Authentication
- `POST /api/register` - Register user
- `POST /api/login` - Login
- `POST /api/logout` - Logout
- `GET /api/me` - Get current user

### Posts
- `GET /api/posts` - List posts
- `POST /api/posts` - Create post
- `GET /api/posts/{id}` - View post
- `PUT /api/posts/{id}` - Update post
- `POST /api/posts/{id}/approve` - Approve (Manager/Admin)
- `POST /api/posts/{id}/reject` - Reject (Manager/Admin)
- `DELETE /api/posts/{id}` - Delete (Admin only)

---

## Advanced Configuration

### Database Options

**MySQL/MariaDB** (default):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=content_approval
```

**PostgreSQL**:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=content_approval
```

**SQLite** (for testing):
```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

---

## Troubleshooting

### Migration Issues
```bash
php artisan migrate:fresh --seed
```

### Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Permission Errors
Ensure storage and bootstrap/cache directories are writable:
```bash
chmod -R 775 storage bootstrap/cache
```

---

## Production Deployment

1. Set `APP_ENV=production` and `APP_DEBUG=false`
2. Run optimizations:
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. Set proper file permissions
4. Configure web server (Nginx/Apache)
5. Use queue workers for background jobs
6. Enable HTTPS with SSL certificate

