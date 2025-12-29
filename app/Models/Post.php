<?php

namespace App\Models;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'body',
        'status',
        'author_id',
        'approved_by',
        'rejected_reason',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => PostStatus::class,
        ];
    }

    /**
     * Get the author of the post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the user who approved the post.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the logs for the post.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(PostLog::class);
    }

    /**
     * Scope a query to only include posts with a specific status.
     */
    public function scopeStatus($query, PostStatus $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include posts by a specific author.
     */
    public function scopeByAuthor($query, int $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    /**
     * Check if the post is pending.
     */
    public function isPending(): bool
    {
        return $this->status === PostStatus::PENDING;
    }

    /**
     * Check if the post is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === PostStatus::APPROVED;
    }

    /**
     * Check if the post is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === PostStatus::REJECTED;
    }

    /**
     * Approve the post.
     */
    public function approve(User $approver): bool
    {
        return $this->update([
            'status' => PostStatus::APPROVED,
            'approved_by' => $approver->id,
            'rejected_reason' => null,
        ]);
    }

    /**
     * Reject the post.
     */
    public function reject(User $rejector, string $reason): bool
    {
        return $this->update([
            'status' => PostStatus::REJECTED,
            'approved_by' => $rejector->id,
            'rejected_reason' => $reason,
        ]);
    }
}
