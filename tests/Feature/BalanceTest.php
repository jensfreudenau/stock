<?php

namespace Tests\Feature;

use App\Models\Balance;
use App\Models\InStock;
use App\Services\StatisticService;
use Carbon\Carbon;
use Tests\TestCase;

class BalanceTest extends TestCase
{

//    public function test_getCorrectSymbol(): void
//    {
//        $inStockBuys = InStock::whereNotNull('buy_at')->orderBy('name')->get();
//        $symbolCollection = $inStockBuys->where('symbol', 'SAP');
//        $this->assertTrue($symbolCollection->contains('symbol', 'SAP'));
//        $this->assertFalse($symbolCollection->contains('symbol', 'AMZN'));
//    }
//
//    public function test_getCorrectBuyingYear()
//    {
//        $inStockBuys = InStock::whereNotNull('buy_at')->where('symbol', 'SAP')
//            ->whereYear('buy_at', '2023')->orderBy('name')->get();
//
//        $this->assertSame('2023', data_get($inStockBuys->first(), 'buy_at')->format('Y'));
//    }
//
//    public function test_correctBuyingSharePrice()
//    {
//        $inStockBuys = InStock::orderBy('id')
//            ->whereNotNull('buy_at')
//            ->where('symbol', 'VST')
//            ->whereYear('buy_at', '2024')->get();
//        $this->assertSame(2, $inStockBuys->count());
//        $sum = 0;
//        foreach ($inStockBuys as $inStockBuy) {
//            $sum += $inStockBuy->price * $inStockBuy->amount;
//        }
//        $this->assertSame(40.0, $inStockBuys->sum('amount'));
//        $this->assertSame(25.0, $sum / $inStockBuys->sum('amount'));
//    }

    public function test_balance2023()
    {
        $inStockBuysAndSells = InStock::orderBy('id')
            ->whereYear('buy_at', '2023')
            ->orWhereYear('sell_at', '2023')->get();
        $vst = $inStockBuysAndSells->where('symbol', 'VST');
        $calculated = $vst->each(function ($item, int $key) {
            $multiplier = 1;
            if (!empty($item->sell_at)) {
                $multiplier = -1;
            }
            return $item['real_price'] = ($item->price * $item->amount) * $multiplier;
        });

        $this->assertSame(600, (int)$calculated->sum('real_price'));
    }

    public function test_balance2024IsNotNull()
    {
        $symbol = 'VST';
        $dealDate  = '2024-10-19';
        $statisticService = new StatisticService();
        $statisticService->calculateDeal($symbol,$dealDate);
        $lostWin = $statisticService->getLostWin();
        $this->assertSame(-825, (int)$lostWin);
    }
}
