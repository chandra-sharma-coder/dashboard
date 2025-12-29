<?php

namespace App\Http\Controllers\API;

use App\Enums\PostStatus;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Services\PostLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    protected PostLogService $logService;

    public function __construct(PostLogService $logService)
    {
        $this->logService = $logService;
    }

    /**
     * Display a listing of posts.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Post::with(['author:id,name,email,role', 'approver:id,name,email']);

        // Authors can only see their own posts
        if ($user->isAuthor()) {
            $query->byAuthor($user->id);
        }
        // Managers and Admins can see all posts

        // Apply status filter if provided
        if ($request->has('status')) {
            $status = PostStatus::tryFrom($request->status);
            if ($status) {
                $query->status($status);
            }
        }

        $posts = $query->latest()->paginate(15);

        return response()->json($posts);
    }

    /**
     * Store a newly created post.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Post::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post = Post::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'status' => PostStatus::PENDING,
            'author_id' => $request->user()->id,
        ]);

        // Log the creation
        $this->logService->logCreated($post, $request->user());

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post->load('author'),
        ], 201);
    }

    /**
     * Display the specified post.
     */
    public function show(Request $request, Post $post)
    {
        Gate::authorize('view', $post);

        $post->load(['author:id,name,email,role', 'approver:id,name,email', 'logs.user:id,name,email']);

        return response()->json($post);
    }

    /**
     * Update the specified post.
     */
    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'body' => 'sometimes|required|string',
        ]);

        $changes = [];
        foreach ($validated as $key => $value) {
            if ($post->$key !== $value) {
                $changes[$key] = [
                    'old' => $post->$key,
                    'new' => $value,
                ];
            }
        }

        $post->update($validated);

        // Log the update if there were changes
        if (!empty($changes)) {
            $this->logService->logUpdated($post, $request->user(), $changes);
        }

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post->load('author'),
        ]);
    }

    /**
     * Approve the specified post.
     */
    public function approve(Request $request, Post $post)
    {
        Gate::authorize('approve', $post);

        if (!$post->isPending()) {
            return response()->json([
                'message' => 'Only pending posts can be approved',
            ], 422);
        }

        $post->approve($request->user());

        // Log the approval
        $this->logService->logApproved($post, $request->user());

        return response()->json([
            'message' => 'Post approved successfully',
            'post' => $post->load(['author', 'approver']),
        ]);
    }

    /**
     * Reject the specified post.
     */
    public function reject(Request $request, Post $post)
    {
        Gate::authorize('reject', $post);

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        if (!$post->isPending()) {
            return response()->json([
                'message' => 'Only pending posts can be rejected',
            ], 422);
        }

        $post->reject($request->user(), $validated['reason']);

        // Log the rejection
        $this->logService->logRejected($post, $request->user(), $validated['reason']);

        return response()->json([
            'message' => 'Post rejected successfully',
            'post' => $post->load(['author', 'approver']),
        ]);
    }

    /**
     * Remove the specified post.
     */
    public function destroy(Request $request, Post $post)
    {
        Gate::authorize('delete', $post);

        // Log the deletion before deleting
        $this->logService->logDeleted($post, $request->user());

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }
}
