<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
    protected $model = \App\Models\Brand::class;

    public function definition()
    {
        return [
            'uuid' => Str::uuid(),
            'title' => $this->faker->word,
            'slug' => $this->faker->slug,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
