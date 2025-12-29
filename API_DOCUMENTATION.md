# API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication
All protected endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## Auth Endpoints

### Register User
**POST** `/register`

Register a new user account.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "author"
}
```

**Roles:** `author`, `manager`, `admin`

**Response (201):**
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "author"
  },
  "token": "1|abcdef123456..."
}
```

---

### Login
**POST** `/login`

Login and receive authentication token.

**Request Body:**
```json
{
  "email": "author@example.com",
  "password": "password"
}
```

**Response (200):**
```json
{
  "message": "Login successful",
  "user": {
    "id": 3,
    "name": "Author User",
    "email": "author@example.com",
    "role": "author"
  },
  "token": "2|xyz789abc..."
}
```

---

### Logout
**POST** `/logout`

Revoke current access token.

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Response (200):**
```json
{
  "message": "Logged out successfully"
}
```

---

### Get Current User
**GET** `/me`

Get authenticated user information.

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Response (200):**
```json
{
  "user": {
    "id": 3,
    "name": "Author User",
    "email": "author@example.com",
    "role": "author"
  }
}
```

---

## Post Endpoints

### List Posts
**GET** `/posts`

Get list of posts (filtered by role).

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Query Parameters:**
- `status` (optional): Filter by status (`pending`, `approved`, `rejected`)
- `page` (optional): Page number for pagination

**Response (200):**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "title": "Introduction to Laravel 12",
      "body": "Laravel 12 brings many exciting features...",
      "status": "pending",
      "author_id": 3,
      "approved_by": null,
      "rejected_reason": null,
      "created_at": "2024-12-29T10:00:00.000000Z",
      "updated_at": "2024-12-29T10:00:00.000000Z",
      "author": {
        "id": 3,
        "name": "Author User",
        "email": "author@example.com",
        "role": "author"
      },
      "approver": null
    }
  ],
  "per_page": 15,
  "total": 5
}
```

**Access Rules:**
- Authors: See only their own posts
- Managers & Admins: See all posts

---

### Create Post
**POST** `/posts`

Create a new post (Authors only).

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Request Body:**
```json
{
  "title": "My New Post",
  "body": "This is the content of my post..."
}
```

**Response (201):**
```json
{
  "message": "Post created successfully",
  "post": {
    "id": 6,
    "title": "My New Post",
    "body": "This is the content of my post...",
    "status": "pending",
    "author_id": 3,
    "approved_by": null,
    "rejected_reason": null,
    "created_at": "2024-12-29T11:00:00.000000Z",
    "updated_at": "2024-12-29T11:00:00.000000Z",
    "author": {
      "id": 3,
      "name": "Author User",
      "email": "author@example.com",
      "role": "author"
    }
  }
}
```

---

### View Single Post
**GET** `/posts/{id}`

Get details of a specific post.

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Response (200):**
```json
{
  "id": 1,
  "title": "Introduction to Laravel 12",
  "body": "Laravel 12 brings many exciting features...",
  "status": "pending",
  "author_id": 3,
  "approved_by": null,
  "rejected_reason": null,
  "created_at": "2024-12-29T10:00:00.000000Z",
  "updated_at": "2024-12-29T10:00:00.000000Z",
  "author": {
    "id": 3,
    "name": "Author User",
    "email": "author@example.com",
    "role": "author"
  },
  "approver": null,
  "logs": [
    {
      "id": 1,
      "post_id": 1,
      "user_id": 3,
      "action": "created",
      "details": {
        "title": "Introduction to Laravel 12",
        "status": "pending"
      },
      "created_at": "2024-12-29T10:00:00.000000Z",
      "user": {
        "id": 3,
        "name": "Author User",
        "email": "author@example.com"
      }
    }
  ]
}
```

**Access Rules:**
- Authors: Can only view their own posts
- Managers & Admins: Can view all posts

---

### Update Post
**PUT** `/posts/{id}`

Update an existing post (Authors can only update their own posts).

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Request Body:**
```json
{
  "title": "Updated Title",
  "body": "Updated content..."
}
```

**Response (200):**
```json
{
  "message": "Post updated successfully",
  "post": {
    "id": 1,
    "title": "Updated Title",
    "body": "Updated content...",
    "status": "pending",
    "author_id": 3,
    "created_at": "2024-12-29T10:00:00.000000Z",
    "updated_at": "2024-12-29T11:30:00.000000Z"
  }
}
```

---

### Approve Post
**POST** `/posts/{id}/approve`

Approve a pending post (Manager/Admin only).

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Response (200):**
```json
{
  "message": "Post approved successfully",
  "post": {
    "id": 1,
    "title": "Introduction to Laravel 12",
    "body": "Laravel 12 brings many exciting features...",
    "status": "approved",
    "author_id": 3,
    "approved_by": 1,
    "rejected_reason": null,
    "created_at": "2024-12-29T10:00:00.000000Z",
    "updated_at": "2024-12-29T12:00:00.000000Z",
    "author": {
      "id": 3,
      "name": "Author User",
      "email": "author@example.com",
      "role": "author"
    },
    "approver": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@example.com",
      "role": "admin"
    }
  }
}
```

**Error Response (422):**
```json
{
  "message": "Only pending posts can be approved"
}
```

---

### Reject Post
**POST** `/posts/{id}/reject`

Reject a pending post with a reason (Manager/Admin only).

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Request Body:**
```json
{
  "reason": "Content needs more technical depth and examples."
}
```

**Response (200):**
```json
{
  "message": "Post rejected successfully",
  "post": {
    "id": 1,
    "title": "Introduction to Laravel 12",
    "body": "Laravel 12 brings many exciting features...",
    "status": "rejected",
    "author_id": 3,
    "approved_by": 1,
    "rejected_reason": "Content needs more technical depth and examples.",
    "created_at": "2024-12-29T10:00:00.000000Z",
    "updated_at": "2024-12-29T12:15:00.000000Z",
    "author": {
      "id": 3,
      "name": "Author User",
      "email": "author@example.com",
      "role": "author"
    },
    "approver": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@example.com",
      "role": "admin"
    }
  }
}
```

---

### Delete Post
**DELETE** `/posts/{id}`

Delete any post (Admin only).

**Headers:**
```
Authorization: Bearer YOUR_TOKEN
```

**Response (200):**
```json
{
  "message": "Post deleted successfully"
}
```

---

## Error Responses

### Unauthenticated (401)
```json
{
  "message": "Unauthenticated."
}
```

### Unauthorized (403)
```json
{
  "message": "Unauthorized. Required role: manager or admin"
}
```

### Not Found (404)
```json
{
  "message": "Post not found"
}
```

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "title": ["The title field is required."],
    "body": ["The body field is required."]
  }
}
```

---

## Testing with cURL

### 1. Login as Author
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "author@example.com",
    "password": "password"
  }'
```

### 2. Create a Post
```bash
curl -X POST http://localhost:8000/api/posts \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "title": "Test Post",
    "body": "This is a test post content."
  }'
```

### 3. Login as Admin
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password"
  }'
```

### 4. Approve a Post
```bash
curl -X POST http://localhost:8000/api/posts/1/approve \
  -H "Authorization: Bearer ADMIN_TOKEN"
```

### 5. List All Posts
```bash
curl -X GET http://localhost:8000/api/posts \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### 6. Delete a Post (Admin only)
```bash
curl -X DELETE http://localhost:8000/api/posts/1 \
  -H "Authorization: Bearer ADMIN_TOKEN"
```

---

## Activity Log

All post actions are automatically logged in the `post_logs` table:

- **created**: When a post is created
- **updated**: When a post is updated
- **approved**: When a post is approved
- **rejected**: When a post is rejected
- **deleted**: When a post is deleted

Logs include:
- Post ID
- User ID (who performed the action)
- Action type
- Additional details (JSON)
- Timestamp

Access logs through the post detail endpoint to see the complete history.
