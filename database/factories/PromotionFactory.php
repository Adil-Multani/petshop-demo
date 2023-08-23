<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PromotionFactory extends Factory
{
    protected $model = \App\Models\Promotion::class;

    public function definition()
    {
        return [
            'uuid' => Str::uuid(),
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'metadata' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
