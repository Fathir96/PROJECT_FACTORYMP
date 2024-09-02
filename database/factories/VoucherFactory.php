<?php

namespace Database\Factories;

use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoucherFactory extends Factory
{
    protected $model = Voucher::class;

    public function definition()
    {
        return [
            'discount_price' => $this->faker->randomFloat(2, 5, 100), // Random discount between 5 and 100
            'expired_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'), // Expiration date up to 1 year from now
            'desc' => $this->faker->sentence(),
        ];
    }
}
