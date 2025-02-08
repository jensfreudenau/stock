<?php

namespace Database\Seeders;


use App\Models\InStock;
use Illuminate\Database\Seeder;

class InStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Using a Factory to generate random data (using Faker library)
        InStock::factory(10)->create();
        InStock::factory(20)->create();
    }
}
