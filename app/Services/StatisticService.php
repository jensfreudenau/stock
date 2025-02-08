<?php

namespace App\Services;

use App\Models\Balance;
use App\Models\InStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StatisticService
{
    private int|float $lost_win;
    private int|float $in_stock;
    private int|float $current_avg_price;
    private string $symbol;
    private string $deal_date;

    public function calculateDeal($symbol, $dealDate)
    {
        $now = Carbon::parse($dealDate);
        $lastDeal = Balance::where('symbol', $symbol)->orderBy('id', 'desc')->first();
        $buyingPrice = 0;
        if(empty($lastDeal)) {
            $firstBuy = InStock::orderBy('id')->where('symbol', $symbol)->first();
            $buyingPrice += $firstBuy->amount * $lastDeal->price;
            $lastDealDate = Carbon::parse($firstBuy->buy_At)->addDay();
            $inStock = $firstBuy->in_stock;
        } else {
            $lastDealDate = Carbon::parse($lastDeal->deal_date)->addDay();
            $buyingPrice += $lastDeal->in_stock * $lastDeal->current_avg_price;
            $inStock = $lastDeal->in_stock;
        }

        $inStockBuysAndSells = InStock::orderBy('id')
            ->whereBetween('buy_at', [$lastDealDate->format('Y-m-d'), $now->format('Y-m-d')])
            ->orWhereBetween('sell_at', [$lastDealDate->format('Y-m-d'), $now->format('Y-m-d')])->get();
        $symbolCollection = $inStockBuysAndSells->where('symbol', $symbol);

        $sellAt = $symbolCollection->whereNotNull('sell_at');
        $buyAt = $symbolCollection->whereNotNull('buy_at');

        foreach ($buyAt as $buyItem) {
            $buyingPrice += $buyItem->amount * $buyItem->price;
        }

        $amount = $buyAt->sum('amount') + $lastDeal->in_stock;
        $sellingValue =  $sellAt->sum('amount') * $sellAt->sum('price');
        $this->lost_win = ($buyingPrice - $sellingValue) * (-1);
        $this->in_stock = ($buyAt->sum('amount') + $lastDeal->in_stock) - $amount;
        $this->current_avg_price = $buyingPrice / $buyAt->sum('price');
        $this->symbol = $symbol;
        $this->deal_date = $now->format('Y-m-d');
    }

    private function getAvgBuyingPrice()
    {
        
    }

    private function getAvgSellingPrice()
    {
        
    }
    public function getLostWin(): float|int
    {
        return $this->lost_win;
    }

    public function save(): void
    {
        Balance::create(get_object_vars($this));
    }

    /**
     * @param $sellAt
     * @return mixed
     */
    public function getSum($sellAt): mixed
    {
        return $sellAt->sum('amount');
    }

}
