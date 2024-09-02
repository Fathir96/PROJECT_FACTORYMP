<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    public function run()
    {
        Voucher::factory(10)->create(); // Creates 10 vouchers using the factory
    }
}
