<?php

namespace Database\Factories;

use App\Models\Delivery;
use Illuminate\Database\Eloquent\Factories\Factory;

class DeliveryFactory extends Factory
{
    protected $model = Delivery::class;

    public function definition()
    {
        return [
            'order_type' => $this->faker->randomElement(['Standard', 'Express', 'Overnight']),
            'extra_protection' => $this->faker->boolean(),
            'shipping_price' => $this->faker->randomFloat(2, 5, 50), // Random price between 5 and 50
        ];
    }
}
