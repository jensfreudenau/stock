<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Transaction::factory()->create([
            'symbol' => 'VST',
            'portfolio_id' => 2,
            'quantity' => 20,
            'buy_quantity' => 20,
            'price' => 20,
            'type' => 'buy',
            'transaction_at' => '2023-04-19'

        ]);
        Transaction::factory()->create([
            'symbol' => 'VST',
            'portfolio_id' => 2,
            'quantity' => 20,
            'buy_quantity' => 0,
            'price' => 20,
            'transaction_at' => '2023-05-19',
            'type' => 'sell',
        ]);
        Transaction::factory()->create([
            'symbol' => 'VST',
            'portfolio_id' => 2,
            'quantity' => 20,
            'buy_quantity' => 20,
            'price' => 20,
            'transaction_at' => '2024-04-19',
            'type' => 'buy',
        ]);
        Transaction::factory()->create([
            'symbol' => 'VST',
            'portfolio_id' => 2,
            'quantity' => 30,
            'buy_quantity' => 30,
            'price' => 30,
            'transaction_at' => '2024-06-19',
            'type' => 'buy',
        ]);
        Transaction::factory()->create([
            'symbol' => 'VST',
            'portfolio_id' => 2,
            'quantity' => 30,
            'buy_quantity' => 0,
            'price' => 40,
            'transaction_at' => '2024-10-19',
            'type' => 'sell',
        ]);
        Transaction::factory()->create([
            'symbol' => 'VST',
            'portfolio_id' => 2,
            'quantity' => 20,
            'buy_quantity' => 20,
            'price' => 30,
            'transaction_at' => '2024-10-21',
            'type' => 'buy',
        ]);
        Transaction::factory()->create([
            'symbol' => 'VST',
            'portfolio_id' => 2,
            'quantity' => 20,
            'buy_quantity' => 20,
            'price' => 30,
            'transaction_at' => '2024-10-22',
            'type' => 'buy',
        ]);
        Transaction::factory()->create([
            'symbol' => 'VST',
            'portfolio_id' => 2,
            'quantity' => 50,
            'buy_quantity' => 0,
            'price' => 40,
            'transaction_at' => '2024-10-23',
            'type' => 'sell',
        ]);
        Transaction::factory()->create([
            'symbol' => 'SAP',
            'portfolio_id' => 1,
            'quantity' => 20,
            'buy_quantity' => 20,
            'price' => 20,
            'transaction_at' => '2024-04-19',
            'type' => 'buy',
        ]);
        Transaction::factory()->create([
            'symbol' => 'SAP',
            'portfolio_id' => 1,
            'quantity' => 20,
            'buy_quantity' => 20,
            'price' => 30,
            'transaction_at' => '2024-06-19',
            'type' => 'buy',
        ]);
        Transaction::factory()->create([
            'symbol' => 'SAP',
            'portfolio_id' => 1,
            'quantity' => 40,
            'buy_quantity' => 0,
            'price' => 100,
            'transaction_at' => '2024-10-19',
            'type' => 'sell',
        ]);

        $this->call([
            TransactionSeeder::class,
        ]);
        // User::factory(10)->create();
//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
    }
}
