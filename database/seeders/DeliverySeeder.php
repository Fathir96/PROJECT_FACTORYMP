<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Delivery;

class DeliverySeeder extends Seeder
{
    public function run()
    {
        Delivery::factory(10)->create(); // Creates 10 deliveries using the factory
    }
}
