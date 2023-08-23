<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogFactory extends Factory
{
    protected $model = \App\Models\Blog::class;

    public function definition()
    {
        return [
            'uuid' => Str::uuid(),
            'title' => $this->faker->sentence,
            'slug' => $this->faker->slug,
            'content' => $this->faker->paragraphs(3, true),
            'metadata' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
