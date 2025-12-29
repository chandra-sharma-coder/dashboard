<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    /**
     * Get the posts authored by the user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }

    /**
     * Get the posts approved by the user.
     */
    public function approvedPosts()
    {
        return $this->hasMany(Post::class, 'approved_by');
    }

    /**
     * Get the post logs for the user.
     */
    public function postLogs()
    {
        return $this->hasMany(PostLog::class);
    }

    /**
     * Check if user is an author.
     */
    public function isAuthor(): bool
    {
        return $this->role === UserRole::AUTHOR;
    }

    /**
     * Check if user is a manager.
     */
    public function isManager(): bool
    {
        return $this->role === UserRole::MANAGER;
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    /**
     * Check if user can approve posts.
     */
    public function canApprove(): bool
    {
        return $this->role->canApprove();
    }

    /**
     * Check if user can delete posts.
     */
    public function canDelete(): bool
    {
        return $this->role->canDelete();
    }
}
