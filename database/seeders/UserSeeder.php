<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create default admin account
        \App\Models\User::create([
            'uuid'              => \Illuminate\Support\Str::uuid(),
            'first_name'        => 'Admin',
            'last_name'         => 'User',
            'is_admin'          => true,
            'email'             => 'admin@buckhill.co.uk',
            'email_verified_at' => now(),
            'password'          => Hash::make('admin'),
            'avatar'            => null,
            'address'           => '123 Admin Street',
            'phone_number'      => '123-456-7890',
            'is_marketing'      => false,
            'created_at'        => now(),
        ]);

        // Create other users using factory
        \App\Models\User::factory(9)->create();
    }
}
