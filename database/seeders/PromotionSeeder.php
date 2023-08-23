<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Promotion::factory(10)->create();
    }
}
