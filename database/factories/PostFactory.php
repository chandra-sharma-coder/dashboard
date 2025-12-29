<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'body' => fake()->paragraphs(3, true),
            'status' => PostStatus::PENDING,
            'author_id' => User::factory(),
            'approved_by' => null,
            'rejected_reason' => null,
        ];
    }

    /**
     * Indicate that the post is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PostStatus::PENDING,
            'approved_by' => null,
            'rejected_reason' => null,
        ]);
    }

    /**
     * Indicate that the post is approved.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PostStatus::APPROVED,
            'approved_by' => User::factory(),
            'rejected_reason' => null,
        ]);
    }

    /**
     * Indicate that the post is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PostStatus::REJECTED,
            'approved_by' => User::factory(),
            'rejected_reason' => fake()->sentence(),
        ]);
    }
}
