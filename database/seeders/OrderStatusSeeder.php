<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderStatusSeeder extends Seeder
{
    public function run()
    {
        $statuses = [
            ['uuid' => Str::uuid(), 'title' => 'open', 'created_at' => now(), 'updated_at' => now()],
            ['uuid' => Str::uuid(), 'title' => 'pending payment', 'created_at' => now(), 'updated_at' => now()],
            ['uuid' => Str::uuid(), 'title' => 'paid', 'created_at' => now(), 'updated_at' => now()],
            ['uuid' => Str::uuid(), 'title' => 'shipped', 'created_at' => now(), 'updated_at' => now()],
            ['uuid' => Str::uuid(), 'title' => 'cancelled', 'created_at' => now(), 'updated_at' => now()],
        ];

        \App\Models\OrderStatus::insert($statuses);
    }
}
