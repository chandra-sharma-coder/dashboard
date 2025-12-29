# Environment Configuration Guide

This file explains each environment variable in the `.env` file.

## Application Settings

### APP_NAME
**Default:** "Content Approval System"  
**Description:** The name of your application, used in notifications and UI.
```env
APP_NAME="Content Approval System"
```

### APP_ENV
**Default:** local  
**Options:** local, development, staging, production  
**Description:** Determines the environment your app is running in.
```env
APP_ENV=local
```

### APP_KEY
**Default:** (generated)  
**Description:** Encryption key for the application. Generate with `php artisan key:generate`
```env
APP_KEY=base64:YourGeneratedKeyHere==
```

### APP_DEBUG
**Default:** true  
**Options:** true, false  
**Description:** Show detailed error messages. **MUST be false in production.**
```env
APP_DEBUG=true
```

### APP_URL
**Default:** http://localhost:8000  
**Description:** The base URL of your application.
```env
APP_URL=http://localhost:8000
```

---

## Database Configuration

### DB_CONNECTION
**Default:** mysql  
**Options:** mysql, mariadb, pgsql, sqlite, sqlsrv  
**Description:** The database driver to use.
```env
DB_CONNECTION=mysql
```

### DB_HOST
**Default:** 127.0.0.1  
**Description:** The database server address.
```env
DB_HOST=127.0.0.1
```

### DB_PORT
**Default:** 3306 (MySQL), 5432 (PostgreSQL)  
**Description:** The database server port.
```env
DB_PORT=3306
```

### DB_DATABASE
**Default:** content_approval  
**Description:** The name of your database.
```env
DB_DATABASE=content_approval
```

### DB_USERNAME
**Default:** root  
**Description:** Database username.
```env
DB_USERNAME=root
```

### DB_PASSWORD
**Default:** (empty)  
**Description:** Database password.
```env
DB_PASSWORD=
```

---

## Logging

### LOG_CHANNEL
**Default:** stack  
**Options:** stack, single, daily, slack, syslog, errorlog  
**Description:** The logging channel to use.
```env
LOG_CHANNEL=stack
```

### LOG_DEPRECATIONS_CHANNEL
**Default:** null  
**Description:** Channel for deprecation warnings.
```env
LOG_DEPRECATIONS_CHANNEL=null
```

### LOG_LEVEL
**Default:** debug  
**Options:** debug, info, notice, warning, error, critical, alert, emergency  
**Description:** Minimum log level to record.
```env
LOG_LEVEL=debug
```

---

## Cache & Session

### CACHE_DRIVER
**Default:** file  
**Options:** file, array, database, redis, memcached  
**Description:** The cache storage driver.
```env
CACHE_DRIVER=file
```

### SESSION_DRIVER
**Default:** file  
**Options:** file, cookie, database, redis, memcached, array  
**Description:** Where to store session data.
```env
SESSION_DRIVER=file
```

### SESSION_LIFETIME
**Default:** 120  
**Description:** Session lifetime in minutes.
```env
SESSION_LIFETIME=120
```

---

## Queue

### QUEUE_CONNECTION
**Default:** sync  
**Options:** sync, database, redis, sqs, beanstalkd  
**Description:** Queue driver for background jobs.
```env
QUEUE_CONNECTION=sync
```

---

## Redis (Optional)

### REDIS_HOST
**Default:** 127.0.0.1  
**Description:** Redis server address.
```env
REDIS_HOST=127.0.0.1
```

### REDIS_PASSWORD
**Default:** null  
**Description:** Redis password (if required).
```env
REDIS_PASSWORD=null
```

### REDIS_PORT
**Default:** 6379  
**Description:** Redis server port.
```env
REDIS_PORT=6379
```

---

## Mail Configuration

### MAIL_MAILER
**Default:** smtp  
**Options:** smtp, sendmail, mailgun, ses, postmark, log, array  
**Description:** Mail driver to use.
```env
MAIL_MAILER=smtp
```

### MAIL_HOST
**Default:** mailpit  
**Description:** SMTP server hostname.
```env
MAIL_HOST=mailpit
```

### MAIL_PORT
**Default:** 1025  
**Description:** SMTP server port.
```env
MAIL_PORT=1025
```

### MAIL_USERNAME
**Default:** null  
**Description:** SMTP username.
```env
MAIL_USERNAME=null
```

### MAIL_PASSWORD
**Default:** null  
**Description:** SMTP password.
```env
MAIL_PASSWORD=null
```

### MAIL_ENCRYPTION
**Default:** null  
**Options:** tls, ssl, null  
**Description:** Email encryption method.
```env
MAIL_ENCRYPTION=null
```

### MAIL_FROM_ADDRESS
**Default:** hello@example.com  
**Description:** Default sender email address.
```env
MAIL_FROM_ADDRESS="hello@example.com"
```

### MAIL_FROM_NAME
**Default:** ${APP_NAME}  
**Description:** Default sender name.
```env
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Filesystem

### FILESYSTEM_DISK
**Default:** local  
**Options:** local, public, s3, ftp  
**Description:** Default filesystem disk.
```env
FILESYSTEM_DISK=local
```

---

## Broadcasting

### BROADCAST_DRIVER
**Default:** log  
**Options:** pusher, redis, log, null  
**Description:** Broadcasting driver for real-time events.
```env
BROADCAST_DRIVER=log
```

---

## Example Configurations

### Development (Local)
```env
APP_NAME="Content Approval System"
APP_ENV=local
APP_KEY=base64:generated-key-here
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=content_approval
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### Production
```env
APP_NAME="Content Approval System"
APP_ENV=production
APP_KEY=base64:your-secure-key-here
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=content_approval
DB_USERNAME=secure-username
DB_PASSWORD=secure-password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=your-redis-host
REDIS_PASSWORD=redis-password
REDIS_PORT=6379
```

### Testing
```env
APP_NAME="Content Approval System"
APP_ENV=testing
APP_KEY=base64:test-key-here
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=content_approval_test
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
```

---

## Security Notes

1. **Never commit `.env` to version control**
2. **Always use strong, unique APP_KEY in production**
3. **Keep database credentials secure**
4. **Set APP_DEBUG=false in production**
5. **Use HTTPS (SSL) in production**
6. **Regularly rotate sensitive credentials**
7. **Use environment-specific configurations**
8. **Enable rate limiting in production**

---

## Common Issues

### Issue: APP_KEY not set
**Solution:**
```bash
php artisan key:generate
```

### Issue: Database connection failed
**Solution:**
- Check DB credentials in .env
- Ensure database exists
- Verify MySQL/PostgreSQL is running
- Check firewall settings

### Issue: Permission denied errors
**Solution:**
```bash
chmod -R 775 storage bootstrap/cache
```

### Issue: Config cached with wrong values
**Solution:**
```bash
php artisan config:clear
php artisan cache:clear
```

---

## Additional Environment Variables (Optional)

### Sanctum Configuration
```env
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1,::1
```

### API Rate Limiting
```env
API_RATE_LIMIT=60
```

### Custom Settings
```env
POSTS_PER_PAGE=15
MAX_POST_LENGTH=10000
APPROVAL_NOTIFICATION=true
```

---

## Environment-Specific Tips

### Local Development
- Use `APP_DEBUG=true` for detailed errors
- Use file-based drivers (cache, session) for simplicity
- Use sync queue for immediate execution

### Staging
- Mirror production settings
- Use separate database
- Enable logging for debugging

### Production
- Set `APP_DEBUG=false`
- Use Redis for caching and sessions
- Use queue workers for background jobs
- Enable all security features
- Use CDN for static assets
- Configure proper backups

---

For more information, visit: https://laravel.com/docs/configuration
