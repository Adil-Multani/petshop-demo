<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = \App\Models\User::class;

    public function definition()
    {
        return [
            'uuid' => Str::uuid(),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'is_admin' => false,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => Hash::make('userpassword'),
            'avatar' => null,
            'address' => $this->faker->address,
            'phone_number' => $this->faker->phoneNumber,
            'is_marketing' => $this->faker->boolean,
            'created_at' => now(),
        ];
    }
}
