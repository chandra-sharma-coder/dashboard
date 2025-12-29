<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can view any posts.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view posts (filtered in controller)
    }

    /**
     * Determine whether the user can view the post.
     */
    public function view(User $user, Post $post): bool
    {
        // Authors can only view their own posts
        if ($user->isAuthor()) {
            return $post->author_id === $user->id;
        }

        // Managers and Admins can view all posts
        return $user->canApprove();
    }

    /**
     * Determine whether the user can create posts.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create posts
        return true;
    }

    /**
     * Determine whether the user can update the post.
     */
    public function update(User $user, Post $post): bool
    {
        // Authors can only update their own posts
        // Managers and Admins cannot update posts
        return $user->isAuthor() && $post->author_id === $user->id;
    }

    /**
     * Determine whether the user can approve the post.
     */
    public function approve(User $user, Post $post): bool
    {
        // Only Managers and Admins can approve
        // Cannot approve own posts
        return $user->canApprove() && $post->author_id !== $user->id;
    }

    /**
     * Determine whether the user can reject the post.
     */
    public function reject(User $user, Post $post): bool
    {
        // Only Managers and Admins can reject
        // Cannot reject own posts
        return $user->canApprove() && $post->author_id !== $user->id;
    }

    /**
     * Determine whether the user can delete the post.
     */
    public function delete(User $user, Post $post): bool
    {
        // Only Admins can delete any post
        return $user->canDelete();
    }

    /**
     * Determine whether the user can restore the post.
     */
    public function restore(User $user, Post $post): bool
    {
        return $user->canDelete();
    }

    /**
     * Determine whether the user can permanently delete the post.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return $user->canDelete();
    }
}
