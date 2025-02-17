<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Profit;
use App\Models\Stock;
use App\Models\Transaction;
use App\Services\DateUtility;
use App\Services\StatisticService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class StatisticController extends Controller
{
    public function index()
    {
        $portfolios = Portfolio::active()->orderBy('symbol')->get();
        $performance = [];
        foreach ($portfolios as $portfolio) {
            $last = Stock::query()->where('symbol', $portfolio->symbol)->orderBy('id', 'desc')->first();
            $performance['current'][] = StatisticService::calculateCurrentValues($last->close, $portfolio->symbol);
        }
        $portfoliosArchives = Portfolio::active(0)->orderBy('symbol')->get();
        foreach ($portfoliosArchives as $portfoliosArchive) {
            $last = Stock::query()->where('symbol', $portfoliosArchive->symbol)->orderBy('id', 'desc')->first();
            $performance['archive'][] = StatisticService::calculateCurrentValues(
                $last->close,
                $portfoliosArchive->symbol
            );
        }
        return view('statistic.index', $performance);
    }

    public function chart($symbol): JsonResponse
    {
        $portfolio = Portfolio::where('symbol', $symbol)->first();

        $chartValues = Stock::query()
            ->select(DB::raw("DATE_FORMAT(stock_date, '%d.%m.%Y') AS stock_date"), 'close')
            ->where('symbol', $symbol)
            ->where('stock_date', '>=', $portfolio->active_since)
            ->pluck('close', 'stock_date');

        return response()->json($chartValues);
    }

    public function sharePerformance($symbol): JsonResponse
    {
        $startLastMonth = (new Carbon('first day of last month'))->format('Y-m-d');
        $endLastMonth = (new Carbon('last day of last month'))->format('Y-m-d');
        $startOfLastWeek = Carbon::now()->subDays(7)->startOfWeek()->format('Y-m-d');
        $endOfLastWeek = Carbon::now()->subDays(7)->endOfWeek()->format('Y-m-d');
        $avgLastMonth = Stock::query()->where('symbol', $symbol)->whereBetween(
            'stock_date',
            array($startLastMonth, $endLastMonth)
        )->avg('close');

        $avgLastWeek = Stock::query()->where('symbol', $symbol)->whereBetween(
            'stock_date',
            array($startOfLastWeek, $endOfLastWeek)
        )->avg('close');

        $performance['day'] = 0;
        $performance['day_profit'] = 0;
        $performance['week'] = 0;
        $performance['week_profit'] = 0;
        $performance['month'] = 0;
        $performance['month_profit'] = 0;
        $performance['overall'] = 0;
        $performance['overall_profit'] = 0;
        $lastWorkday = DateUtility::getLastWorkday(Carbon::today());
        $dayBeforeYesterday = $lastWorkday->subDays(1)->format('Y-m-d');
        $avgYesterday = Stock::query()->where('symbol', $symbol)->where('stock_date', $dayBeforeYesterday)->first();
        if ($avgYesterday) {
            $last = Stock::query()->where('symbol', $symbol)->orderBy('id', 'desc')->first();
            $currentPerformance = StatisticService::calculateCurrentValues($last->close, $symbol);
            $performance['day'] = $last->close - $avgYesterday->close;
            $performance['day_profit'] = ($last->close * $currentPerformance['remainingShares'] - $avgYesterday->close * $currentPerformance['remainingShares']);
            $performance['week'] = $last->close - $avgLastWeek;
            $performance['week_profit'] = ($last->close * $currentPerformance['remainingShares'] - $avgLastWeek * $currentPerformance['remainingShares']);
            $performance['month'] = $last->close - $avgLastMonth;
            $performance['month_profit'] = ($last->close * $currentPerformance['remainingShares'] - $avgLastMonth * $currentPerformance['remainingShares']);
            $performance['overall'] = $last->close - $currentPerformance['averagePurchasePrice'];
            $performance['overall_profit'] = ($last->close * $currentPerformance['remainingShares'] - $currentPerformance['averagePurchasePrice'] * $currentPerformance['remainingShares']);
        }
        return response()->json($performance);
    }

    public function getProfitsByYear($year): JsonResponse
    {
        $date = Carbon::createFromDate($year, 1, 1);
        $startOfYear = $date->copy()->startOfYear()->format('Y-m-d');
        $endOfYear = $date->copy()->endOfYear()->format('Y-m-d');
        $inStockBuysAndSells = Profit::orderBy('transaction_at', 'desc')
            ->whereBetween('transaction_at', [$startOfYear, $endOfYear])
            ->get()->toArray();
        $sum = Profit::orderBy('transaction_at')
            ->whereBetween('transaction_at', [$startOfYear, $endOfYear])
            ->sum('profit');
        $inStockBuysAndSells['sum_profit'] = $sum;
        return response()->json($inStockBuysAndSells);
    }

    public function getShareSalesVolumeByYear($year): JsonResponse
    {
        $date = Carbon::createFromDate($year, 1, 1);
        $startOfYear = $date->copy()->startOfYear()->format('Y-m-d');
        $endOfYear = $date->copy()->endOfYear()->format('Y-m-d');
        $inStockBuysAndSells = Transaction::orderBy('id')
            ->whereBetween('transaction_at', [$startOfYear, $endOfYear])
            ->get();
        $shares = Transaction::getAllShares();
        $outcome = 0;
        $income = 0;
        foreach ($shares as $share) {
            $symbol = $inStockBuysAndSells->where('symbol', $share->symbol);
//            $calculated[] = $symbol->each(function ($item) {
//                $multiplier = 1;
//                if (!empty($item->sell_at)) {
//                    $multiplier = -1;
//                    return $item['outcome_price'] = ($item->price * $item->amount) * $multiplier;
//                }
//                return $item['income_price'] = ($item->price * $item->amount) * $multiplier;
//            });
        }
        $data = [];
//        foreach ($calculated as $key => $item) {
//            $first = $item->first();
//            if ($first) {
//                $data[$key]['symbol'] = $first->symbol;
//                $data[$key]['income_price'] = $item->sum('income_price');
//                $data[$key]['outcome_price'] = $item->sum('outcome_price');
//                $outcome += $data[$key]['outcome_price'];
//                $income += $data[$key]['income_price'];
//            }
//        }
//        $data['outcome'] = $outcome;
//        $data['income'] = $income;

        return response()->json($data);
    }
}
