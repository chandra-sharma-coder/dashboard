# Application Workflows

## User Role Hierarchy

```
┌─────────────────────────────────────────┐
│              ADMIN                       │
│  • All Manager permissions               │
│  • Delete any post                       │
│  • Full system access                    │
└─────────────────┬───────────────────────┘
                  │
┌─────────────────▼───────────────────────┐
│             MANAGER                      │
│  • View all posts                        │
│  • Approve posts                         │
│  • Reject posts with reason              │
└─────────────────┬───────────────────────┘
                  │
┌─────────────────▼───────────────────────┐
│             AUTHOR                       │
│  • Create posts                          │
│  • Update own posts                      │
│  • View only own posts                   │
└─────────────────────────────────────────┘
```

---

## Post Lifecycle Workflow

```
┌─────────────┐
│   AUTHOR    │
│  Creates    │
│    Post     │
└──────┬──────┘
       │
       ▼
┌─────────────┐
│   PENDING   │ ◄───────────────┐
│   STATUS    │                 │
└──────┬──────┘                 │
       │                        │
       │ Submitted for Review   │
       │                        │
       ▼                        │
┌─────────────────┐             │
│  MANAGER/ADMIN  │             │
│    Reviews      │             │
└────────┬────────┘             │
         │                      │
    ┌────┴────┐                 │
    │         │                 │
    ▼         ▼                 │
┌────────┐ ┌────────┐           │
│APPROVE │ │REJECT  │           │
└───┬────┘ └───┬────┘           │
    │          │                │
    │          │ (with reason)  │
    │          │                │
    ▼          ▼                │
┌────────┐ ┌────────┐           │
│APPROVED│ │REJECTED│           │
│ STATUS │ │ STATUS │           │
└────────┘ └───┬────┘           │
              │                 │
              │ Author can      │
              │ edit and        │
              │ resubmit        │
              └─────────────────┘
```

---

## API Request Flow

```
┌──────────────┐
│   CLIENT     │
│  (Postman/   │
│   Frontend)  │
└──────┬───────┘
       │
       │ HTTP Request + Bearer Token
       │
       ▼
┌──────────────────────┐
│   Laravel Routes     │
│   (routes/api.php)   │
└──────┬───────────────┘
       │
       │ Route Matched
       │
       ▼
┌──────────────────────┐
│   Sanctum Auth       │
│   Middleware         │
└──────┬───────────────┘
       │
       │ Token Valid?
       │
       ├─── NO ──► 401 Unauthenticated
       │
       │ YES
       ▼
┌──────────────────────┐
│   Role Middleware    │
│   (if applicable)    │
└──────┬───────────────┘
       │
       │ Role Check
       │
       ├─── NO ──► 403 Unauthorized
       │
       │ YES
       ▼
┌──────────────────────┐
│   Controller         │
│   (PostController)   │
└──────┬───────────────┘
       │
       │ Process Request
       │
       ▼
┌──────────────────────┐
│   Policy Check       │
│   (PostPolicy)       │
└──────┬───────────────┘
       │
       │ Authorized?
       │
       ├─── NO ──► 403 Forbidden
       │
       │ YES
       ▼
┌──────────────────────┐
│   Business Logic     │
│   (Service Layer)    │
└──────┬───────────────┘
       │
       │ Execute Action
       │
       ▼
┌──────────────────────┐
│   Database           │
│   (Models/Eloquent)  │
└──────┬───────────────┘
       │
       │ Record Updated
       │
       ▼
┌──────────────────────┐
│   Activity Logger    │
│   (PostLogService)   │
└──────┬───────────────┘
       │
       │ Log Created
       │
       ▼
┌──────────────────────┐
│   JSON Response      │
│   (200/201/422...)   │
└──────┬───────────────┘
       │
       ▼
┌──────────────┐
│   CLIENT     │
│   Receives   │
│   Response   │
└──────────────┘
```

---

## Database Relationships

```
┌──────────────┐
│    USERS     │
│──────────────│
│ id (PK)      │
│ name         │
│ email        │
│ password     │
│ role         │
└──────┬───────┘
       │
       │ 1:N (author)
       │
       ▼
┌──────────────┐
│    POSTS     │
│──────────────│
│ id (PK)      │
│ title        │
│ body         │
│ status       │
│ author_id FK │◄──┐
│ approved_by  │   │ N:1 (approver)
└──────┬───────┘   │
       │           │
       │ 1:N       └───────┐
       │                   │
       ▼           ┌───────┴───────┐
┌──────────────┐   │    USERS      │
│  POST_LOGS   │   │  (approver)   │
│──────────────│   └───────────────┘
│ id (PK)      │
│ post_id FK   │
│ user_id FK   │
│ action       │
│ details JSON │
└──────────────┘
       ▲
       │ N:1
       │
┌──────┴───────┐
│    USERS     │
│  (actor)     │
└──────────────┘
```

---

## Activity Logging Flow

```
┌──────────────────┐
│  User Action     │
│  (Create/Update/ │
│   Approve/etc)   │
└────────┬─────────┘
         │
         ▼
┌──────────────────┐
│  Controller      │
│  Processes       │
│  Request         │
└────────┬─────────┘
         │
         │ Action Successful
         │
         ▼
┌──────────────────┐
│  PostLogService  │
│  log() method    │
└────────┬─────────┘
         │
         │ Create Log Entry
         │
         ▼
┌──────────────────┐
│  POST_LOGS       │
│  Table Insert    │
│──────────────────│
│  post_id: 1      │
│  user_id: 3      │
│  action: created │
│  details: {...}  │
│  timestamp       │
└──────────────────┘
         │
         │ Log Saved
         │
         ▼
┌──────────────────┐
│  Response to     │
│  Client          │
└──────────────────┘
```

---

## Authentication Flow

```
┌─────────────────┐
│  User Login     │
│  Request        │
│  POST /api/login│
└────────┬────────┘
         │
         │ email + password
         │
         ▼
┌─────────────────┐
│  AuthController │
│  login()        │
└────────┬────────┘
         │
         │ Validate Credentials
         │
    ┌────┴────┐
    │         │
 INVALID   VALID
    │         │
    │         ▼
    │    ┌─────────────────┐
    │    │  Create Token   │
    │    │  via Sanctum    │
    │    └────────┬────────┘
    │             │
    │             │ Token: "1|abc123..."
    │             │
    │             ▼
    │    ┌─────────────────┐
    │    │  Return User    │
    │    │  + Token        │
    │    └────────┬────────┘
    │             │
    ▼             ▼
┌─────────┐  ┌─────────┐
│  401    │  │  200 OK │
│ Invalid │  │ Success │
└─────────┘  └─────────┘

Subsequent Requests:

┌─────────────────┐
│  API Request    │
│  with Token     │
│  in Header      │
└────────┬────────┘
         │
         │ Authorization: Bearer TOKEN
         │
         ▼
┌─────────────────┐
│  Sanctum        │
│  Middleware     │
└────────┬────────┘
         │
         │ Verify Token
         │
    ┌────┴────┐
    │         │
 INVALID   VALID
    │         │
    │         ▼
    │    ┌─────────────────┐
    │    │  Attach User    │
    │    │  to Request     │
    │    └────────┬────────┘
    │             │
    │             │ $request->user()
    │             │
    │             ▼
    │    ┌─────────────────┐
    │    │  Process        │
    │    │  Request        │
    │    └─────────────────┘
    │
    ▼
┌─────────┐
│  401    │
│Unauthent│
└─────────┘
```

---

## Authorization Decision Tree

```
Can User Access Resource?
│
├─ Is User Authenticated?
│  ├─ NO ──► 401 Unauthenticated
│  └─ YES
│     │
│     └─ Check Required Role
│        │
│        ├─ Role Mismatch ──► 403 Unauthorized
│        └─ Role Match
│           │
│           └─ Check Policy
│              │
│              ├─ Action: VIEW POST
│              │  ├─ Is Author? ──► Can view own posts
│              │  ├─ Is Manager/Admin? ──► Can view all posts
│              │  └─ Other ──► 403 Forbidden
│              │
│              ├─ Action: UPDATE POST
│              │  ├─ Is Author + Own Post? ──► Allowed
│              │  └─ Other ──► 403 Forbidden
│              │
│              ├─ Action: APPROVE/REJECT
│              │  ├─ Is Manager/Admin? ──► Allowed
│              │  └─ Other ──► 403 Forbidden
│              │
│              └─ Action: DELETE
│                 ├─ Is Admin? ──► Allowed
│                 └─ Other ──► 403 Forbidden
```

---

## Testing Workflow

```
┌─────────────────┐
│  Run Test Suite │
│  php artisan    │
│  test           │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  PHPUnit        │
│  Initialization │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Refresh        │
│  Test Database  │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Run Migrations │
│  & Factories    │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  Execute Tests  │
│  • Unit Tests   │
│  • Feature Tests│
└────────┬────────┘
         │
    ┌────┴────┐
    │         │
  PASS      FAIL
    │         │
    │         ▼
    │    ┌─────────────────┐
    │    │  Show Error     │
    │    │  Details        │
    │    └─────────────────┘
    │
    ▼
┌─────────────────┐
│  Test Report    │
│  All Green ✓    │
└─────────────────┘
```

---

## Deployment Checklist

```
□ Environment Configuration
  ├─ □ Update .env
  ├─ □ Set APP_ENV=production
  ├─ □ Set APP_DEBUG=false
  ├─ □ Generate APP_KEY
  └─ □ Configure database

□ Dependencies
  ├─ □ composer install --no-dev
  └─ □ composer dump-autoload -o

□ Database
  ├─ □ Run migrations
  └─ □ Seed initial data

□ Optimization
  ├─ □ php artisan config:cache
  ├─ □ php artisan route:cache
  ├─ □ php artisan view:cache
  └─ □ php artisan optimize

□ Security
  ├─ □ Set file permissions
  ├─ □ Configure HTTPS
  ├─ □ Enable CORS if needed
  └─ □ Set up rate limiting

□ Monitoring
  ├─ □ Configure logging
  ├─ □ Set up error tracking
  └─ □ Enable performance monitoring

□ Backup
  ├─ □ Database backups
  └─ □ File backups
```

This visual documentation helps understand the complete application flow and decision-making processes.
