<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Order::factory(50)->create();
    }
}
