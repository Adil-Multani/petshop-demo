<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Payment;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        $paymentTypes = ['credit_card', 'cash_on_delivery', 'bank_transfer'];

        return [
            'uuid' => Str::uuid(),
            'type' => $this->faker->randomElement($paymentTypes),
            'details' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
