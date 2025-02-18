<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Profit;
use App\Models\Stock;
use App\Services\StatisticService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Number;


class StatisticController extends Controller
{
    public function index(): Application|Factory|View
    {
        $portfolios = Portfolio::active()->orderBy('symbol')->get();
        $activeSymbols = [];
        $profitTemp = 0;
        foreach ($portfolios as $key => $portfolio) {
            $activeSymbols[$key]['symbol'] = $portfolio->symbol;
            $activeSymbols[$key]['name'] = $portfolio->name;
            $price = Stock::where('symbol', $portfolio->symbol)->orderBy('id', 'desc')->first();
            $current = StatisticService::calculateCurrentValues($price->close, $portfolio->symbol);
            $profitTemp += $current['profitLoss'];
        }
        $portfoliosArchives = Portfolio::active(0)->orderBy('symbol')->get();
        $archivedSymbols = [];
        $profit = 0;
        foreach ($portfoliosArchives as $key => $portfoliosArchive) {
            $profits = Profit::where('symbol', $portfoliosArchive->symbol)->get();
            foreach ($profits as $profitAction) {
                $profit += $profitAction->profit;
            }
            $archivedSymbols[$key]['symbol'] = $portfoliosArchive->symbol;
            $archivedSymbols[$key]['name'] = $portfoliosArchive->name;
        }
        $profit = Number::currency($profit / 100, 'EUR');
        $profitTemp = Number::currency($profitTemp / 100, 'EUR');

        return view('statistic.index', compact('archivedSymbols', 'activeSymbols', 'profit', 'profitTemp'));
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
        $performance['day'] = 0;
        $performance['day_profit'] = 0;
        $performance['week'] = 0;
        $performance['week_profit'] = 0;
        $performance['month'] = 0;
        $performance['month_profit'] = 0;
        $performance['overall'] = 0;
        $performance['overall_profit'] = 0;

        $startLastMonth = (new Carbon('first day of last month'))->format('Y-m-d');
        $endLastMonth = (new Carbon('last day of last month'))->format('Y-m-d');
        $startOfLastWeek = Carbon::now()->subDays(7)->startOfWeek()->format('Y-m-d');
        $endOfLastWeek = Carbon::now()->subDays(7)->endOfWeek()->previousWeekday()->format('Y-m-d');
        $avgLastMonth = Stock::query()
            ->where('symbol', $symbol)
            ->whereBetween('stock_date', array($startLastMonth, $endLastMonth))
            ->avg('close');

        $avgLastWeek = Stock::query()
            ->where('symbol', $symbol)
            ->whereBetween('stock_date', array($startOfLastWeek, $endOfLastWeek))
            ->avg('close');

        $today = Carbon::now();
        $dayBefore = $today->previousWeekday();
        $previousWeekday = Stock::query()->where('symbol', $symbol)->where(
            'stock_date',
            $dayBefore->format('Y-m-d')
        )->first();
        $dayBeforePreviousWeekday = Stock::query()->where('symbol', $symbol)->where(
            'stock_date',
            $dayBefore->previousWeekday()->format('Y-m-d')
        )->first();
        $last = Stock::query()->where('symbol', $symbol)->orderBy('id', 'desc')->first();
        $currentPerformance = StatisticService::calculateCurrentValues($last->close, $symbol);
        if ($previousWeekday && $dayBeforePreviousWeekday) {
            $performance['day'] = $previousWeekday->close - $dayBeforePreviousWeekday->close;
            $performance['day_profit'] = ($previousWeekday->close - $dayBeforePreviousWeekday->close) * $currentPerformance['remainingShares'];
        }
        if ($avgLastWeek) {
            $performance['week'] = $currentPerformance['averagePurchasePrice'] - $avgLastWeek;
            $performance['week_profit'] = ($last->close * $currentPerformance['remainingShares'] - $avgLastWeek * $currentPerformance['remainingShares']);
        }

        if ($avgLastMonth) {
            $performance['month'] = $currentPerformance['averagePurchasePrice'] - $avgLastMonth;
            $performance['month_profit'] = ($last->close * $currentPerformance['remainingShares'] - $avgLastMonth * $currentPerformance['remainingShares']);
        }

        $performance['overall'] = $last->close - $currentPerformance['averagePurchasePrice'];
        $performance['overall_profit'] = ($last->close * $currentPerformance['remainingShares'] - $currentPerformance['averagePurchasePrice'] * $currentPerformance['remainingShares']);

        return response()->json($performance);
    }

    public function active(
        $symbol
    ): JsonResponse {
        $price = Stock::where('symbol', $symbol)->orderBy('id', 'desc')->first();
        $currentValues = StatisticService::calculateCurrentValues($price->close, $symbol);

        return response()->json($currentValues);
    }

    public function archive(
        $symbol
    ): JsonResponse {
        $price = Stock::where('symbol', $symbol)->orderBy('id', 'desc')->first();
        $currentValues = StatisticService::calculatePastValues($price->close, $symbol);

        return response()->json($currentValues);
    }

    public function getProfitsByYear(
        $year
    ): JsonResponse {
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
}
