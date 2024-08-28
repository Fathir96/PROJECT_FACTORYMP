<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;  

use Database\Seeders\ProductsTableSeeder;
use Database\Seeders\StoreSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\BrandSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            StoreSeeder::class,
            ProductsTableSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
        ]); 
    }
}
