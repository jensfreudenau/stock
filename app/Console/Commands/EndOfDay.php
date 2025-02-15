<?php

namespace App\Console\Commands;

use App\APIHelper\AlphaVantageApi;
use App\APIHelper\DeutscheBoerseApi;

use App\APIHelper\FillShare;
use App\Models\Portfolio;
use App\Models\Stock;
use Carbon\Carbon;
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
            if (empty($stocks)) {
                $stockDate = Carbon::now();
            } else {
                $stockDate = $stocks->stock_date;
            }
            $shares = null;
            if ($portfolio->share_type === 'etf') {
                $shares = new FillShare($portfolio->symbol, new DeutscheBoerseApi());
            }
            else {
                $shares = new FillShare($portfolio->symbol, new AlphaVantageApi());
            }
            if (empty($shares)) {
                continue;
            }
            $data = $shares->fillHistory();
            if (empty($data)) {
                continue;
            }
            if ($portfolio->share_type === 'etf') {
                $all['symbol'] = $portfolio->symbol;
                $all['portfolio_id'] = $portfolio->id;
                $all['stock_date'] = $stockDate;
                $all['open'] = $data['open'] * 100;
                $all['low'] = $data['low'] * 100;
                $all['high'] = $data['high'] * 100;
                $all['close'] = $data['close'] * 100;
                $all['volume'] = 0;
                Stock::create($all);
                continue;
            }
            $collect = collect($data);
            $filtered = $collect->where('stock_date', '>=', $stockDate);

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
