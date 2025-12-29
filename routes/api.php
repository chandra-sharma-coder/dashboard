<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Post routes
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    
    // Post approval/rejection routes (Manager/Admin)
    Route::post('/posts/{post}/approve', [PostController::class, 'approve'])
        ->middleware('role:manager,admin');
    Route::post('/posts/{post}/reject', [PostController::class, 'reject'])
        ->middleware('role:manager,admin');
    
    // Post deletion route (Admin only)
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
        ->middleware('role:admin');
});
