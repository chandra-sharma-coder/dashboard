<?php

namespace App\Services;

use App\Enums\PostAction;
use App\Models\Post;
use App\Models\PostLog;
use App\Models\User;

class PostLogService
{
    /**
     * Log a post action.
     */
    public function log(Post $post, User $user, PostAction $action, ?array $details = null): PostLog
    {
        return PostLog::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'action' => $action,
            'details' => $details,
        ]);
    }

    /**
     * Log post creation.
     */
    public function logCreated(Post $post, User $user): PostLog
    {
        return $this->log($post, $user, PostAction::CREATED, [
            'title' => $post->title,
            'status' => $post->status->value,
        ]);
    }

    /**
     * Log post update.
     */
    public function logUpdated(Post $post, User $user, array $changes): PostLog
    {
        return $this->log($post, $user, PostAction::UPDATED, [
            'changes' => $changes,
        ]);
    }

    /**
     * Log post approval.
     */
    public function logApproved(Post $post, User $user): PostLog
    {
        return $this->log($post, $user, PostAction::APPROVED, [
            'previous_status' => 'pending',
            'new_status' => 'approved',
        ]);
    }

    /**
     * Log post rejection.
     */
    public function logRejected(Post $post, User $user, string $reason): PostLog
    {
        return $this->log($post, $user, PostAction::REJECTED, [
            'previous_status' => 'pending',
            'new_status' => 'rejected',
            'reason' => $reason,
        ]);
    }

    /**
     * Log post deletion.
     */
    public function logDeleted(Post $post, User $user): PostLog
    {
        return $this->log($post, $user, PostAction::DELETED, [
            'title' => $post->title,
            'status' => $post->status->value,
        ]);
    }

    /**
     * Get logs for a post.
     */
    public function getPostLogs(Post $post)
    {
        return PostLog::forPost($post->id)
            ->with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
