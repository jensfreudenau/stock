<?php

namespace App\Console\Commands;

use App\APIHelper\ETFApi;
use App\APIHelper\FillShare;
use App\APIHelper\SharesApi;
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
                $shares = new FillShare($portfolio->symbol, $portfolio->isin, new ETFApi());
            } else {
                $shares = new FillShare($portfolio->symbol, $portfolio->isin, new SharesApi());
            }
            if (empty($shares)) {
                continue;
            }
            $datas = $shares->fillHistory();
            if (empty($datas)) {
                continue;
            }

            if ($portfolio->share_type === 'etf') {
                foreach ($datas as $data) {
                    $all['symbol'] = $portfolio->symbol;
                    $all['isin'] = $portfolio->symbol;
                    $all['portfolio_id'] = $portfolio->id;
                    $all['stock_date'] = $data['stock_date'];
                    $all['close'] = $data['close'];
                    $all['volume'] = 0;
                    Stock::create($all);
                    continue 2;
                }

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
