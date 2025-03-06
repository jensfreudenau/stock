<?php

namespace App\Console\Commands;

use App\APIHelper\ETFApi;
use App\Models\Portfolio;
use App\Models\SavingsPlan;
use App\Models\Transaction;
use Illuminate\Console\Command;

class BuySavingsPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:buy_savings-plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $plans = SavingsPlan::where('buy_at', date('d'))->get();
        foreach ($plans as $plan) {
            $portfolio = Portfolio::find($plan->portfolio_id);
            $current = (new ETFApi())->fillCurrent($portfolio);
            $transactionData = [
                'portfolio_id' => $portfolio->id,
                'symbol' => $portfolio->symbol,
                'type' => 'buy',
                'quantity' => $plan->quantity / ($current->close / 100),
                'buy_quantity' => $plan->quantity / ($current->close / 100),
                'price' => $current->close,
            ];
            Transaction::create($transactionData);
        }
    }
}
