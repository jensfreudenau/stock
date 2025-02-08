<?php

namespace Database\Seeders;

use App\Models\InStock;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        InStock::factory()->create([
            'symbol' => 'VST',
            'name' => 'blub',
            'amount' => 20,
            'price' => 20,
            'buy_at' => '2023-04-19',
            'sell_at' => NULL,
        ]);
        InStock::factory()->create([
            'symbol' => 'VST',
            'name' => 'blub',
            'amount' => 20,
            'price' => 20,
            'buy_at' => NULL,
            'sell_at' => '2023-05-19',
        ]);
        InStock::factory()->create([
            'symbol' => 'VST',
            'name' => 'blub',
            'amount' => 20,
            'price' => 20,
            'buy_at' => '2024-04-19',
            'sell_at' => NULL,
        ]);
        InStock::factory()->create([
            'symbol' => 'VST',
            'name' => 'blub',
            'amount' => 20,
            'price' => 30,
            'buy_at' => '2024-06-19',
            'sell_at' => NULL,
        ]);
        InStock::factory()->create([
            'symbol' => 'VST',
            'name' => 'blub',
            'amount' => 40,
            'price' => 10,
            'sell_at' => '2024-10-19',
            'buy_at' => NULL,
        ]);

        InStock::factory()->create([
            'symbol' => 'SAP',
            'name' => 'SAP blub',
            'amount' => 20,
            'price' => 20,
            'buy_at' => '2023-04-19',
            'sell_at' => NULL,
        ]);
        InStock::factory()->create([
            'symbol' => 'SAP',
            'name' => 'SAP blub',
            'amount' => 20,
            'price' => 30,
            'buy_at' => '2023-06-19',
            'sell_at' => NULL,
        ]);
        InStock::factory()->create([
            'symbol' => 'SAP',
            'name' => 'SAP blub',
            'amount' => 40,
            'price' => 100,
            'sell_at' => '2023-10-19',
            'buy_at' => NULL,
        ]);

//        $this->call([
//            InStockSeeder::class,
//        ]);
        // User::factory(10)->create();
//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
    }
}
