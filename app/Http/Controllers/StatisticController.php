<?php

namespace App\Http\Controllers;

use App\Models\InStock;
use App\Models\Portfolio;
use App\Models\Sell;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class StatisticController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::with('InStocks')->get();
        foreach ($portfolios as $portfolio) {

            $buyAmount = $sellAmount = $buyPrice = $sellPrice = $total = $buy = $avgPrice = 0;
            foreach ($portfolio->inStocks as $key => $stock) {
                if (!$stock->sell_at) {
                    $buyAmount += $stock->amount;
                    $buy += $stock->price * $stock->amount;
                    $portfolio->inStocks[$key]->balance -= $stock->amount * $stock->price;
                    $buyPrice = $stock->price;
                    $avgPrice = $buy / $buyAmount;
                } else {
                    $sellAmount += $stock->amount;
                    $sellPrice = $stock->price;
                    $portfolio->inStocks[$key]->balance += $stock->amount * $stock->price;
                    $total = ($stock->amount* $stock->price) - ($stock->amount* max($avgPrice,1));
                }
                $portfolio->inStocks[$key]->diff = ($sellPrice - $buyPrice) * $sellAmount;
                $portfolio->inStocks[$key]->buy_amount = $buyAmount;
                $portfolio->inStocks[$key]->sell_amount = $sellAmount;
                $portfolio->inStocks[$key]->total_amount = $portfolio->inStocks[$key]->buy_amount - $portfolio->inStocks[$key]->sell_amount;
                $portfolio->inStocks[$key]->total = $total;
                $portfolio->inStocks[$key]->avgPrice = $avgPrice;

//                if($stock->sell_at) {
//                    $data['symbol'] = $stock->symbol;
//                    $data['price_single'] = $stock->price;
//                    $data['revenue'] = $portfolio->inStocks[$key]->balance;
//                    $data['sales_proceeds'] = $total;
//                    $data['sell_date'] = $stock->sell_at;
//                    $data['amount'] = $stock->amount;
//                    $data['portfolio_id'] = $portfolio->id;
//                    Sell::create($data);
//                }
                if($buyAmount - $sellAmount === 0.0) {
                    $avgPrice = $buyAmount=$sellAmount=$buy= 0;
                }
            }
        }

    }

    public function charts()
    {
        $thisYearShares = Stock::query()
            ->whereYear('stock_date', '2024')
            ->where('symbol', 'SAP')
            ->selectRaw('week(stock_date) as week')
            ->selectRaw('count(*) as count')
            ->selectRaw('sum(close)/COUNT(*) as close')
            ->groupBy('week')
            ->orderBy('week')
            ->pluck('close', 'week');

        return response()->json($thisYearShares);
    }

    public function chart($symbol): JsonResponse
    {
        $portfolio = Portfolio::where('symbol', $symbol)->first();
        $chartValues = Stock::query()
            ->select(DB::raw("DATE_FORMAT(stock_date, '%d.%m.%Y') AS stock_date"), 'close')
            ->where('symbol', $symbol)
            ->where('stock_date', '>', $portfolio->active_since)
            ->pluck('close', 'stock_date');

        return response()->json($chartValues);
    }

    public function sharePerformance($symbol)
    {
        $currentDate = \Carbon\Carbon::now();
        $agoDate = $currentDate->startOfWeek()->subWeek();
        $query = Stock::query()
            ->where('symbol', $symbol);
        $query->whereBetween('stock_date', array($agoDate,$currentDate));
        Log::debug(response()->json($query->get()));
//        dump($query->get());
//        SELECT DATE_ADD(DATE_ADD(CURRENT_DATE, INTERVAL -WEEKDAY(CURRENT_DATE) DAY), INTERVAL -1 WEEK) AS start_of_last_week;
//
//-- end of last week (begin: monday)
//SELECT DATE_ADD(DATE_ADD(CURRENT_DATE, INTERVAL -WEEKDAY(CURRENT_DATE)+6 DAY), INTERVAL -1 WEEK) AS end_of_last_week;
        $shares = Stock::query()
            ->where('symbol', $symbol)
            ->selectRaw('week(stock_date) as week')
            ->selectRaw('day(stock_date) as day')
            ->selectRaw('year(stock_date) as year')
            ->get();
        return response()->json($shares);
    }
    public function getShareSalesVolumeByYear($year): JsonResponse
    {
        $date = Carbon::createFromDate($year, 1, 1);
        $startOfYear = $date->copy()->startOfYear()->format('Y-m-d');
        $endOfYear = $date->copy()->endOfYear()->format('Y-m-d');
        $inStockBuysAndSells = InStock::orderBy('id')
            ->whereBetween('buy_at', [$startOfYear, $endOfYear])
            ->orWhereBetween('sell_at', [$startOfYear, $endOfYear])->get();
        $shares = InStock::getAllShares();
        $outcome = 0;
        $income = 0;
        foreach ($shares as $share) {
            $symbol = $inStockBuysAndSells->where('symbol', $share->symbol);
            $calculated[] = $symbol->each(function ($item) {
                $multiplier = 1;
                if (!empty($item->sell_at)) {
                    $multiplier = -1;
                    return $item['outcome_price'] = ($item->price * $item->amount) * $multiplier;
                }
                return $item['income_price'] = ($item->price * $item->amount) * $multiplier;
            });
        }
        $data = [];
        foreach ($calculated as $key => $item) {
            $first = $item->first();
            if ($first) {
                $data[$key]['symbol'] = $first->symbol;
                $data[$key]['income_price'] = $item->sum('income_price');
                $data[$key]['outcome_price'] = $item->sum('outcome_price');
                $outcome += $data[$key]['outcome_price'];
                $income += $data[$key]['income_price'];
            }
        }
        $data['outcome'] = $outcome;
        $data['income'] = $income;

        return response()->json($data);
    }
}
