<?php

namespace App\Console\Commands;

use App\APIHelper\AlphaVantageApi;
use App\APIHelper\ETFApi;
use App\APIHelper\FillShare;
use App\Models\Portfolio;
use App\Models\Stock;
use Illuminate\Console\Command;

class EndOfDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:end-of-day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get the last missing data from API server';

    public function handle(): void
    {
        $portfolios = Portfolio::where('active', true)->get();
        foreach ($portfolios as $portfolio) {
            $stocks = Stock::where('portfolio_id', $portfolio->id)->orderBy('stock_date', 'desc')->first();
            $shares = null;
            if ($portfolio->share_type === 'etf') {
                $shares = new FillShare($portfolio->symbol, new ETFApi());
            } else {
                $shares = new FillShare($portfolio->symbol, new AlphaVantageApi());
            }
            $data = $shares->fillHistory();
            if (empty($data)) {
                continue;
            }
            $collect = collect($data);
            $filtered = $collect->where('stock_date', '>', $stocks->stock_date);
            foreach ($filtered->all() as $all) {
                $all['portfolio_id'] = $portfolio->id;
                $all['open'] = $all['open'] * 100;
                $all['low'] = $all['low'] * 100;
                $all['high'] = $all['high'] * 100;
                $all['close'] = $all['close'] * 100;
                Stock::create($all);
            }
        }
    }
}
