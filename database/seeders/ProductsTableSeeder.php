<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Product;


class ProductsTableSeeder extends Seeder
{


    public function run()
    {
        // $now = now();
        // DB::table('products')->insert([
        //     'name' => 'Produk A',
        //     'price' => 100,
        //     'stock' => 50,
        //     'created_at' => $now,
        //     'updated_at' => $now,
        // ]);

        // DB::table('products')->insert([
        //     'name' => 'Produk B',
        //     'price' => 200,
        //     'stock' => 100,
        //     'created_at' => $now,
        //     'updated_at' => $now,
        // ]);
        Product::factory(100)->create();
    }
}