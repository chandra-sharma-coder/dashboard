# Role-Based Content Approval System

A Laravel 12 application implementing a clean role-based content approval workflow with activity logging.

## Features

- **User Roles**: Author, Manager, Admin
- **Post Management**: Create, update, view posts with approval workflow
- **Activity Logging**: Track all post-related actions
- **REST API**: Clean API endpoints for all operations
- **Authorization**: Policy-based access control

## Installation

### Windows Users
**If you're on Windows and don't have PHP installed yet**, please see **[WINDOWS_SETUP.md](WINDOWS_SETUP.md)** for detailed installation instructions including:
- Laragon (recommended for beginners)
- Docker Desktop
- Manual PHP installation
- XAMPP

### Quick Start (If PHP is already installed)

1. Install dependencies:
```bash
composer install
```

2. Copy environment file:
```bash
cp .env.example .env
```

3. Generate application key:
```bash
php artisan key:generate
```

4. Configure your database in `.env`:
```
DB_DATABASE=content_approval
DB_USERNAME=root
DB_PASSWORD=
```

5. Run migrations:
```bash
php artisan migrate
```

6. Seed the database:
```bash
php artisan db:seed
```

7. Start the development server:
```bash
php artisan serve
```

### Docker Installation (Cross-platform)

See **[DOCKER_GUIDE.md](DOCKER_GUIDE.md)** for Docker setup instructions.

```bash
docker-compose up -d
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
```

## API Endpoints

### Authentication
- `POST /api/login` - Login and get token
- `POST /api/register` - Register new user

### Posts
- `GET /api/posts` - List posts (filtered by role)
- `POST /api/posts` - Create new post (Author)
- `GET /api/posts/{id}` - View single post
- `PUT /api/posts/{id}` - Update post (Author - own posts)
- `POST /api/posts/{id}/approve` - Approve post (Manager/Admin)
- `POST /api/posts/{id}/reject` - Reject post (Manager/Admin)
- `DELETE /api/posts/{id}` - Delete post (Admin only)

## Default Users

After seeding, you can login with:

- **Admin**: admin@example.com / password
- **Manager**: manager@example.com / password
- **Author**: author@example.com / password

## Database Schema

### Users
- id, name, email, password, role, timestamps

### Posts
- id, title, body, status, author_id, approved_by, rejected_reason, timestamps

### Post Logs
- id, post_id, user_id, action, details, timestamps

## Architecture

- **Models**: User, Post, PostLog
- **Controllers**: API/PostController, API/AuthController
- **Policies**: PostPolicy
- **Middleware**: RoleMiddleware
- **Services**: PostLogService
- **Enums**: UserRole, PostStatus, PostAction

## Testing

Run the test suite:
```bash
php artisan test
```

## License

MIT License
