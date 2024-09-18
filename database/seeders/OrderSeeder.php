<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Manually ensure that the IDs referenced are valid
        DB::table('orders')->insert([
            'order_date' => '2024-09-18',
            'description' => 'Example description',
            'user_id' => 1, // Make sure this ID exists in users table
            'product_id' => 1, // Make sure this ID exists in products table
            'voucher_id' => null, // Can be null
            'payment_id' => 1, // Make sure this ID exists in payments table
            'delivery_id' => 1, // Make sure this ID exists in deliveries table
            'destination_address' => '1234 Example Street',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
