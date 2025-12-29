<?php

namespace Database\Seeders;

use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = User::where('role', 'author')->get();
        $admin = User::where('role', 'admin')->first();

        // Create some sample posts
        $posts = [
            [
                'title' => 'Introduction to Laravel 12',
                'body' => 'Laravel 12 brings many exciting features including improved performance, better developer experience, and new architectural patterns.',
                'status' => PostStatus::PENDING,
            ],
            [
                'title' => 'Understanding Role-Based Access Control',
                'body' => 'RBAC is a crucial security feature that helps manage user permissions effectively. In this article, we explore best practices.',
                'status' => PostStatus::APPROVED,
                'approved_by' => $admin?->id,
            ],
            [
                'title' => 'Building RESTful APIs',
                'body' => 'RESTful APIs are the backbone of modern web applications. Learn how to design and implement them properly.',
                'status' => PostStatus::PENDING,
            ],
            [
                'title' => 'Database Optimization Tips',
                'body' => 'Optimizing database queries is essential for application performance. Here are some tips and tricks.',
                'status' => PostStatus::REJECTED,
                'approved_by' => $admin?->id,
                'rejected_reason' => 'Content needs more technical depth and examples.',
            ],
            [
                'title' => 'Clean Code Principles',
                'body' => 'Writing clean, maintainable code is an art. This post covers essential principles every developer should know.',
                'status' => PostStatus::APPROVED,
                'approved_by' => $admin?->id,
            ],
        ];

        foreach ($posts as $index => $postData) {
            $author = $authors[$index % $authors->count()] ?? $authors->first();
            
            Post::create([
                'title' => $postData['title'],
                'body' => $postData['body'],
                'status' => $postData['status'],
                'author_id' => $author->id,
                'approved_by' => $postData['approved_by'] ?? null,
                'rejected_reason' => $postData['rejected_reason'] ?? null,
            ]);
        }
    }
}
