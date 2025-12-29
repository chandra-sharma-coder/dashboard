<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Content Approval System API',
        'version' => '1.0',
        'endpoints' => [
            'POST /api/register' => 'Register a new user',
            'POST /api/login' => 'Login and get token',
            'POST /api/logout' => 'Logout (revoke token)',
            'GET /api/me' => 'Get authenticated user',
            'GET /api/posts' => 'List posts (filtered by role)',
            'POST /api/posts' => 'Create a new post',
            'GET /api/posts/{id}' => 'View a single post',
            'PUT /api/posts/{id}' => 'Update a post (author only)',
            'POST /api/posts/{id}/approve' => 'Approve a post (manager/admin)',
            'POST /api/posts/{id}/reject' => 'Reject a post (manager/admin)',
            'DELETE /api/posts/{id}' => 'Delete a post (admin only)',
        ],
    ]);
});
