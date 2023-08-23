<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = \App\Models\Product::class;

    public function definition()
    {
        $category = \App\Models\Category::factory()->create();

        return [
            'category_uuid' => $category->uuid,
            'uuid' => Str::uuid(),
            'title' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'description' => $this->faker->paragraph,
            'metadata' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
