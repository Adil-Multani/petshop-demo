<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        \App\Models\Payment::factory(50)->create();
    }
}
