<?php

namespace Database\Factories;

use App\Models\Portfolio;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(50, 100);
        $transactionAt = '2024-02-17';
        $portfolio = Portfolio::select('symbol')->where('active', true)->get();
        $symbol = $this->faker->randomElement($portfolio->toArray());
        $quantiySells = Transaction::where('symbol', $symbol['symbol'])->where('type', 'buy')->sum('quantity');
        $type = 'buy';
        $portfolioId = Portfolio::select('id')->where('symbol', $symbol['symbol'])->first();

        if ($this->faker->boolean()) {
            $transactionAt = $this->faker->dateTimeBetween('-2 years', '-1 month');

        } elseif ($quantiySells) {
            $last = Transaction::where('symbol', $symbol['symbol'])
                ->where('type', 'buy')
                ->select('transaction_at')
                ->orderBy('transaction_at')
                ->first();
            $transactionAt = $this->faker->dateTimeBetween($last['transaction_at'], '-1 week');
            $type = 'sell';
            $quantiySells = Transaction::where('symbol', $symbol['symbol'])->where('type', 'buy')->sum('quantity');
            $quantity = $this->faker->numberBetween(10, $quantiySells);
        }
        return [
            'symbol' => $symbol['symbol'],
            'portfolio_id' => $portfolioId->id,
            'quantity' => $quantity,
            'buy_quantity' => $quantity,
            'price' => $this->faker->randomNumber(4, true),
            'type' => $type,
            'transaction_at' => $transactionAt,
        ];
    }
}
