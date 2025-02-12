<?php

namespace Database\Seeders;


use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Using a Factory to generate random data (using Faker library)
        Transaction::factory(10)->create();
        Transaction::factory(20)->create();
    }
}
