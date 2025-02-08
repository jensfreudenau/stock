<?php

namespace App\Http\Controllers;

use App\Models\InStock;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;

class BalanceController extends Controller
{
    public function index(): View|Factory|Application
    {
        return view('balance.index');
    }

    public function balance(): JsonResponse
    {
        $stocks = InStock::orderBy('symbol', 'asc')->orderBy('buy_at', 'desc')->get();
        return response()->json($stocks);
    }

    public function statistic(): void
    {



        $yearsSell = InStock::selectRaw('extract(year FROM sell_at) AS year')
            ->whereNotNull('sell_at')
            ->distinct()
            ->orderBy('year', 'desc')->get();

        foreach ($yearsSell as $year) {
            $inStockSells = InStock::whereYear('buy_at', $year['year'])->get();
            foreach ($inStockSells as $yearsSell) {
                $sells[$year['year']][$yearsSell->symbol][$yearsSell->buy_at->format('Y-m-d')] = $yearsSell->price;
            }
        }

//        $yearsBuy = InStock::selectRaw('extract(year FROM buy_at) AS year')
//            ->whereNotNull('buy_at')
//            ->distinct()
//            ->orderBy('year', 'desc')->get();
        $inStockBuys = InStock::whereNotNull('buy_at')->orderBy('name')->get();
        dump($inStockBuys);

        $sap = $inStockBuys->where('symbol', 'AMZN');

//        $p = $sap->sum('price');
//        dump($p);
        $date = new Carbon('2023-09-30');
        $orders = $sap->filter(function ($item) use ($date) {
            return (data_get($item, 'buy_at') < $date);
        });
        $buyPrice = 0;
        foreach ($orders as $order) {
            $buyPrice += $order->price * $order->amount;
        }
        dump($buyPrice);
        dump($orders);
        dump($orders->sum('amount'));
    }
}
