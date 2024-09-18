<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        return [
            'order_date' => $this->faker->date(),
            'description' => $this->faker->sentence(),
            'user_id' => $this->faker->numberBetween(1, 10),
            'product_id' => $this->faker->numberBetween(1, 10),
            'voucher_id' => $this->faker->numberBetween(1, 10),
            'payment_id' => $this->faker->numberBetween(1, 10),
            'delivery_id' => $this->faker->numberBetween(1, 10),
            'destination_address' => $this->faker->address(),
        ];
    }
}
