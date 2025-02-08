<?php

namespace Database\Factories;

use App\Models\InStock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Model>
 */
class InStockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = $this->faker->numberBetween(500, 1000);
        $buyAt = '2024-02-17';
        $sellAt = null;
        $symbol = $this->faker->randomElement(['AAPL', 'AMZN', 'AVGO', 'ETSY', 'MTX.DE']);
        $amountSells = InStock::where('symbol', $symbol)->whereNotNull('buy_at')->sum('amount');
        if ( $this->faker->boolean()) {
            $buyAt = $this->faker->dateTimeBetween('-2 years', '-1 month');
        } elseif ($amountSells) {
            $last = InStock::where('symbol', $symbol)
                ->whereNotNull('buy_at')
                ->select('buy_at')
                ->orderBy('buy_at')
                ->first();
            $sellAt = $this->faker->dateTimeBetween($last['buy_at'], '-1 week');
            $buyAt = null;
            $amountSells = InStock::where('symbol', $symbol)->whereNotNull('buy_at')->sum('amount');
            $amount = $this->faker->numberBetween(10, $amountSells);
        }

        return [
            'symbol' => $symbol,
            'name' => $this->faker->word(),
            'amount' => $amount,
            'price' => $this->faker->randomFloat(2, 10, 500),
            'buy_at' => $buyAt,
            'sell_at' => $sellAt,
        ];
    }
}
