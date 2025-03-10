<?php

namespace App\Console\Commands;

use App\APIHelper\ETFApi;
use App\APIHelper\FillShare;
use App\APIHelper\SharesApi;
use App\Events\StopLossReached;
use App\Models\Configuration;
use App\Models\Portfolio;
use App\Models\Stock;
use App\Models\StopLoss;
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
            if ($portfolio->share_type === 'etf') {
                $shares = new FillShare($portfolio, new ETFApi());
            } else {
                $shares = new FillShare($portfolio, new SharesApi());
            }
            if (Configuration::getHistory()) {
                $this->insertHistory($shares);
            } else {
                $this->insertCurrent($shares, $portfolio);
            }
        }
    }

    private function insertCurrent($shares, $portfolio): void
    {
        $shareValue = $shares->fillCurrent();
        if (empty($shareValue)) {
            return;
        }
        $stopLoss = StopLoss::where('portfolio_id', $portfolio->id)->first();
        if (!empty($stopLoss) && ($stopLoss->value > $shareValue->close)) {
            event(new StopLossReached($portfolio->name, $shareValue->close));
        }
        $this->updateOrCreate($shareValue);
    }

    private function insertHistory($shares): void
    {
        $shareValues = $shares->fillHistory();
        if (empty($shareValues)) {
            return;
        }
        foreach ($shareValues as $shareValue) {
            $this->updateOrCreate($shareValue);
        }
    }

    private function updateOrCreate($shareValue): void
    {
        $allOld['portfolio_id'] = $shareValue->portfolioId;
        $allOld['stock_date'] = $shareValue->stockDate;
        $allNew['symbol'] = $shareValue->symbol;
        $allNew['isin'] = $shareValue->symbol;
        $allNew['close'] = $shareValue->close;
        Stock::updateOrCreate($allOld, $allNew);
    }
}
