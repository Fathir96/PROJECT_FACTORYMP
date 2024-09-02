<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'method' => $this->faker->creditCardType(),
            'number_id' => $this->faker->creditCardNumber(),
        ];
    }
}
