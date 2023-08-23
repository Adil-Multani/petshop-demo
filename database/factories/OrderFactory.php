<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use App\Models\Payment;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        $userIds = User::pluck('id')->toArray();
        $statusIds = OrderStatus::pluck('id')->toArray();
        $paymentIds = Payment::pluck('id')->toArray();

        $productCount = $this->faker->numberBetween(1, 10);
        $products = [];

        for ($i = 0; $i < $productCount; $i++) {
            $products[] = Str::uuid();
        }

        return [
            'user_id' => $this->faker->randomElement($userIds),
            'order_status_id' => $this->faker->randomElement($statusIds),
            'payment_id' => $this->faker->randomElement($paymentIds),
            'uuid' => Str::uuid(),
            'products' => json_encode($products),
            'address' => json_encode([
                'billing' => $this->faker->address,
                'shipping' => $this->faker->address,
            ]),
            'delivery_fee' => $this->faker->randomFloat(2, 0, 10),
            'amount' => $this->faker->randomFloat(2, 10, 100),
            'created_at' => now(),
            'updated_at' => now(),
            'shipped_at' => $this->faker->optional(0.7)->dateTimeThisMonth(),
        ];
    }
}
