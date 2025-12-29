<?php

namespace Tests\Feature;

use App\Enums\PostStatus;
use App\Enums\UserRole;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $author;
    protected User $manager;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->author = User::factory()->create(['role' => UserRole::AUTHOR]);
        $this->manager = User::factory()->create(['role' => UserRole::MANAGER]);
        $this->admin = User::factory()->create(['role' => UserRole::ADMIN]);
    }

    /** @test */
    public function author_can_create_post()
    {
        $response = $this->actingAs($this->author)
            ->postJson('/api/posts', [
                'title' => 'Test Post',
                'body' => 'This is a test post content.',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'post' => ['id', 'title', 'body', 'status', 'author_id'],
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'author_id' => $this->author->id,
            'status' => PostStatus::PENDING->value,
        ]);
    }

    /** @test */
    public function author_can_only_view_own_posts()
    {
        $ownPost = Post::factory()->create(['author_id' => $this->author->id]);
        $otherPost = Post::factory()->create();

        $response = $this->actingAs($this->author)
            ->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['id' => $ownPost->id])
            ->assertJsonMissing(['id' => $otherPost->id]);
    }

    /** @test */
    public function author_can_update_own_post()
    {
        $post = Post::factory()->create(['author_id' => $this->author->id]);

        $response = $this->actingAs($this->author)
            ->putJson("/api/posts/{$post->id}", [
                'title' => 'Updated Title',
                'body' => 'Updated content',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Title',
        ]);
    }

    /** @test */
    public function author_cannot_update_others_post()
    {
        $post = Post::factory()->create(); // Another author's post

        $response = $this->actingAs($this->author)
            ->putJson("/api/posts/{$post->id}", [
                'title' => 'Updated Title',
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function manager_can_approve_post()
    {
        $post = Post::factory()->create([
            'status' => PostStatus::PENDING,
            'author_id' => $this->author->id,
        ]);

        $response = $this->actingAs($this->manager)
            ->postJson("/api/posts/{$post->id}/approve");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => PostStatus::APPROVED->value]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'status' => PostStatus::APPROVED->value,
            'approved_by' => $this->manager->id,
        ]);
    }

    /** @test */
    public function manager_can_reject_post_with_reason()
    {
        $post = Post::factory()->create([
            'status' => PostStatus::PENDING,
            'author_id' => $this->author->id,
        ]);

        $response = $this->actingAs($this->manager)
            ->postJson("/api/posts/{$post->id}/reject", [
                'reason' => 'Needs improvement',
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => PostStatus::REJECTED->value]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'status' => PostStatus::REJECTED->value,
            'rejected_reason' => 'Needs improvement',
        ]);
    }

    /** @test */
    public function author_cannot_approve_post()
    {
        $post = Post::factory()->create([
            'status' => PostStatus::PENDING,
            'author_id' => $this->author->id,
        ]);

        $response = $this->actingAs($this->author)
            ->postJson("/api/posts/{$post->id}/approve");

        $response->assertStatus(403);
    }

    /** @test */
    public function only_admin_can_delete_post()
    {
        $post = Post::factory()->create();

        // Manager cannot delete
        $response = $this->actingAs($this->manager)
            ->deleteJson("/api/posts/{$post->id}");
        $response->assertStatus(403);

        // Admin can delete
        $response = $this->actingAs($this->admin)
            ->deleteJson("/api/posts/{$post->id}");
        $response->assertStatus(200);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    /** @test */
    public function manager_can_view_all_posts()
    {
        Post::factory()->count(5)->create();

        $response = $this->actingAs($this->manager)
            ->getJson('/api/posts');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function activity_log_is_created_on_post_creation()
    {
        $response = $this->actingAs($this->author)
            ->postJson('/api/posts', [
                'title' => 'Test Post',
                'body' => 'Test content',
            ]);

        $postId = $response->json('post.id');

        $this->assertDatabaseHas('post_logs', [
            'post_id' => $postId,
            'user_id' => $this->author->id,
            'action' => 'created',
        ]);
    }

    /** @test */
    public function cannot_approve_already_approved_post()
    {
        $post = Post::factory()->create([
            'status' => PostStatus::APPROVED,
        ]);

        $response = $this->actingAs($this->manager)
            ->postJson("/api/posts/{$post->id}/approve");

        $response->assertStatus(422)
            ->assertJsonFragment(['message' => 'Only pending posts can be approved']);
    }
}
