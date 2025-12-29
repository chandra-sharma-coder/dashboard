# Documentation Index

Welcome to the Role-Based Content Approval System documentation. This index will help you navigate all available documentation files.

---

## 📋 Quick Start

1. **[START_HERE.md](START_HERE.md)** - ⭐ READ THIS FIRST (especially for Windows)
2. **[WINDOWS_SETUP.md](WINDOWS_SETUP.md)** - Windows installation guide (PHP not installed)
3. **[DOCKER_GUIDE.md](DOCKER_GUIDE.md)** - Docker setup and commands
4. **[README.md](README.md)** - Project overview
5. **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)** - API endpoints reference

---

## 📚 Core Documentation

### Getting Started
- **[START_HERE.md](START_HERE.md)**  
  Quick start guide - read this first if you're on Windows or don't have PHP installed

- **[WINDOWS_SETUP.md](WINDOWS_SETUP.md)**  
  Complete Windows installation guide including Laragon, Docker, manual PHP installation, and XAMPP options

- **[DOCKER_GUIDE.md](DOCKER_GUIDE.md)**  
  Docker setup, commands, troubleshooting, and container management

- **[README.md](README.md)**  
  Project overview, features, installation, and quick start guide

- **[SETUP_GUIDE.md](SETUP_GUIDE.md)**  
  Detailed setup instructions, testing guide, troubleshooting, and deployment tips

- **[ENV_GUIDE.md](ENV_GUIDE.md)**  
  Complete environment configuration reference with examples for development, staging, and production

### API Reference
- **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)**  
  Complete REST API documentation with examples, error codes, and cURL commands

- **[postman_collection.json](postman_collection.json)**  
  Ready-to-import Postman collection for API testing

### Architecture
- **[PROJECT_SUMMARY.md](PROJECT_SUMMARY.md)**  
  Comprehensive project summary including architecture, design patterns, database schema, and best practices

- **[WORKFLOWS.md](WORKFLOWS.md)**  
  Visual workflow diagrams including user roles, post lifecycle, API flow, and deployment checklist

---

## 🗂️ File Structure Guide

### Application Core
```
app/
├── Enums/              → Type-safe enumerations
├── Http/
│   ├── Controllers/    → Request handlers
│   └── Middleware/     → Request filters
├── Models/             → Database models
├── Policies/           → Authorization logic
├── Providers/          → Service providers
└── Services/           → Business logic
```

### Configuration
```
config/
├── app.php            → Application configuration
├── auth.php           → Authentication settings
├── database.php       → Database connections
└── sanctum.php        → API authentication
```

### Database
```
database/
├── factories/         → Model factories for testing
├── migrations/        → Database schema
└── seeders/           → Sample data generators
```

### Routes
```
routes/
├── api.php           → API endpoints
├── console.php       → CLI commands
└── web.php           → Web routes
```

### Tests
```
tests/
├── Feature/          → Integration tests
└── Unit/             → Unit tests
```

---

## 🎯 Documentation by Task

### I want to...

#### Install the Application
1. **Windows without PHP?** Read [START_HERE.md](START_HERE.md) first!
2. **Windows users:** Follow [WINDOWS_SETUP.md](WINDOWS_SETUP.md)
3. **Using Docker:** Follow [DOCKER_GUIDE.md](DOCKER_GUIDE.md)
4. **PHP installed:** Read [SETUP_GUIDE.md](SETUP_GUIDE.md)
5. Configure [ENV_GUIDE.md](ENV_GUIDE.md)

#### Use the API
1. Read [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - All endpoints
2. Import [postman_collection.json](postman_collection.json) to Postman
3. Test with default accounts from [SETUP_GUIDE.md](SETUP_GUIDE.md)

#### Understand the Architecture
1. Read [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) - Architecture section
2. Review [WORKFLOWS.md](WORKFLOWS.md) - Visual diagrams
3. Check code structure in file directories

#### Deploy to Production
1. Read [SETUP_GUIDE.md](SETUP_GUIDE.md) - Production Deployment
2. Configure [ENV_GUIDE.md](ENV_GUIDE.md) - Production settings
3. Follow [WORKFLOWS.md](WORKFLOWS.md) - Deployment Checklist

#### Extend the System
1. Understand [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) - Design patterns
2. Review existing code in `app/` directory
3. Follow Laravel 12 conventions
4. Write tests in `tests/` directory

#### Troubleshoot Issues
1. Check [SETUP_GUIDE.md](SETUP_GUIDE.md) - Troubleshooting section
2. Review [ENV_GUIDE.md](ENV_GUIDE.md) - Common issues
3. Check logs in `storage/logs/`
4. Verify environment configuration

---

## 📊 Feature Documentation

### User Roles & Permissions
- **Overview:** [README.md](README.md) - Features section
- **Details:** [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) - User Roles
- **Workflow:** [WORKFLOWS.md](WORKFLOWS.md) - User Role Hierarchy
- **Implementation:** `app/Enums/UserRole.php` and `app/Policies/PostPolicy.php`

### Post Management
- **API Endpoints:** [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - Post Endpoints
- **Lifecycle:** [WORKFLOWS.md](WORKFLOWS.md) - Post Lifecycle Workflow
- **Model:** `app/Models/Post.php`
- **Controller:** `app/Http/Controllers/API/PostController.php`

### Activity Logging
- **Overview:** [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) - Activity Logging
- **Flow:** [WORKFLOWS.md](WORKFLOWS.md) - Activity Logging Flow
- **Service:** `app/Services/PostLogService.php`
- **Model:** `app/Models/PostLog.php`

### Authentication
- **API Reference:** [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - Auth Endpoints
- **Flow Diagram:** [WORKFLOWS.md](WORKFLOWS.md) - Authentication Flow
- **Controller:** `app/Http/Controllers/API/AuthController.php`
- **Config:** `config/auth.php` and `config/sanctum.php`

### Authorization
- **Policy:** `app/Policies/PostPolicy.php`
- **Middleware:** `app/Http/Middleware/RoleMiddleware.php`
- **Decision Tree:** [WORKFLOWS.md](WORKFLOWS.md) - Authorization Decision Tree
- **Provider:** `app/Providers/AuthServiceProvider.php`

---

## 🧪 Testing Documentation

### Test Files
- **Feature Tests:** `tests/Feature/PostManagementTest.php`
- **Test Configuration:** `phpunit.xml`
- **Factories:** `database/factories/`

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/PostManagementTest.php

# Run with coverage
php artisan test --coverage
```

### Test Coverage
See [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) - Testing section

---

## 🔧 Configuration Files

### Environment
- **[.env](.env)** - Current environment configuration
- **[.env.example](.env.example)** - Template for new installations
- **[ENV_GUIDE.md](ENV_GUIDE.md)** - Detailed configuration guide

### Application
- **[composer.json](composer.json)** - PHP dependencies
- **[phpunit.xml](phpunit.xml)** - Testing configuration
- **[.gitignore](.gitignore)** - Version control exclusions

### Laravel
- **config/app.php** - Application settings
- **config/auth.php** - Authentication configuration
- **config/database.php** - Database connections
- **config/sanctum.php** - API authentication

---

## 🚀 Quick Reference

### Default Test Accounts
| Role    | Email                 | Password |
|---------|-----------------------|----------|
| Admin   | admin@example.com     | password |
| Manager | manager@example.com   | password |
| Author  | author@example.com    | password |

### Common Commands
```bash
# Start development server
php artisan serve

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Run tests
php artisan test

# Clear cache
php artisan cache:clear

# Generate application key
php artisan key:generate
```

### API Base URL
```
http://localhost:8000/api
```

### Key Endpoints
- `POST /api/login` - Login
- `GET /api/posts` - List posts
- `POST /api/posts` - Create post
- `POST /api/posts/{id}/approve` - Approve post (Manager/Admin)
- `DELETE /api/posts/{id}` - Delete post (Admin)

---

## 📖 Additional Resources

### Laravel Documentation
- [Laravel 12 Docs](https://laravel.com/docs)
- [Eloquent ORM](https://laravel.com/docs/eloquent)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Policies](https://laravel.com/docs/authorization#creating-policies)

### Project Files
- Database Migrations: `database/migrations/`
- Routes: `routes/api.php`
- Models: `app/Models/`
- Controllers: `app/Http/Controllers/API/`
- Tests: `tests/Feature/`

---

## 💡 Tips for Different Users

### For Developers
1. Start with [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) for architecture
2. Review [WORKFLOWS.md](WORKFLOWS.md) for flow diagrams
3. Study code in `app/` directory
4. Run tests to understand functionality
5. Follow Laravel 12 best practices

### For API Consumers
1. Read [API_DOCUMENTATION.md](API_DOCUMENTATION.md) thoroughly
2. Import [postman_collection.json](postman_collection.json)
3. Test with default accounts
4. Check error response formats
5. Review authentication flow

### For DevOps/Deployment
1. Follow [SETUP_GUIDE.md](SETUP_GUIDE.md) deployment section
2. Configure [ENV_GUIDE.md](ENV_GUIDE.md) for production
3. Use [WORKFLOWS.md](WORKFLOWS.md) deployment checklist
4. Set up monitoring and backups
5. Configure web server (Nginx/Apache)

### For Project Managers
1. Read [README.md](README.md) for overview
2. Check [PROJECT_SUMMARY.md](PROJECT_SUMMARY.md) for features
3. Review [WORKFLOWS.md](WORKFLOWS.md) for processes
4. Understand user roles and permissions
5. Review test coverage

---

## 🆘 Getting Help

### Documentation Issues
If you find any issues in the documentation:
1. Check if information is in another doc file
2. Review the relevant code files
3. Check Laravel official documentation
4. Create an issue with specific details

### Common Questions
- **Q: How do I install?**  
  A: See [SETUP_GUIDE.md](SETUP_GUIDE.md)

- **Q: What are the API endpoints?**  
  A: See [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

- **Q: How does authorization work?**  
  A: See [WORKFLOWS.md](WORKFLOWS.md) - Authorization Decision Tree

- **Q: How do I deploy?**  
  A: See [SETUP_GUIDE.md](SETUP_GUIDE.md) - Production Deployment

- **Q: Where are the tests?**  
  A: See `tests/Feature/PostManagementTest.php`

---

## 📝 Contributing

When adding new features:
1. Update relevant documentation
2. Add tests
3. Follow existing patterns
4. Update this index if needed

---

## 📅 Version History

- **v1.0** - Initial release with complete role-based approval system
  - User roles (Author, Manager, Admin)
  - Post management with approval workflow
  - Activity logging
  - REST API with authentication
  - Comprehensive documentation

---

## 📜 License

MIT License - See project files for details

---

**Last Updated:** December 29, 2025

For questions or updates, refer to the specific documentation files listed above.
