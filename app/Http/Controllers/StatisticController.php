<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Profit;
use App\Models\Stock;
use App\Services\DateUtility;
use App\Services\StatisticService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Number;

class StatisticController extends Controller
{
    public function index(): Application|Factory|View
    {
        $portfolios = Portfolio::active()->orderBy('symbol')->get();
        $activeSymbols = [];
        $profitTemp = 0;
        foreach ($portfolios as $key => $portfolio) {
            $activeSymbols[$key]['id'] = $portfolio->id;
            $activeSymbols[$key]['portfolio_id'] = $portfolio->id;
            $activeSymbols[$key]['symbol'] = $portfolio->symbol;
            $activeSymbols[$key]['name'] = $portfolio->name;
            $price = Stock::where('symbol', $portfolio->symbol)->orderBy('id', 'desc')->first();
            $current = StatisticService::calculateCurrentValues($price->close, $portfolio->id);
            if ($current === false) {
                continue;
            }
            $profitTemp += $current['profitLoss'];
        }
        $portfoliosArchives = Portfolio::active(0)->orderBy('symbol')->get();
        $archivedSymbols = [];
        $profit = 0;
        foreach ($portfoliosArchives as $key => $portfoliosArchive) {
            $profits = Profit::where('portfolio_id', $portfoliosArchive->id)->get();
            foreach ($profits as $profitAction) {
                $profit += $profitAction->profit;
            }
            $archivedSymbols[$key]['id'] = $portfoliosArchive->id;
            $archivedSymbols[$key]['symbol'] = $portfoliosArchive->symbol;
            $archivedSymbols[$key]['name'] = $portfoliosArchive->name;
        }
        $profit = Number::currency($profit / 100, 'EUR');
        $profitTemp = Number::currency($profitTemp / 100, 'EUR');

        return view('statistic.index', compact('archivedSymbols', 'activeSymbols', 'profit', 'profitTemp'));
    }

    public function chart($id): JsonResponse
    {
        $portfolio = Portfolio::where('id', $id)->first();
        $chartValues = Stock::query()
//            ->select(DB::raw("DATE_FORMAT(stock_date, '%d.%m.%Y') AS stock_date"), 'close')
            ->where('portfolio_id', $id)
            ->where('stock_date', '>=', $portfolio->active_since)
            ->orderBy('stock_date')
            ->pluck('close', 'stock_date');
        //x: '2016-12-25', y: 20
        $data = [];
        $chartData = [];
        foreach ($chartValues as $key => $chartValue) {
            $data['x'] = $key;
            $data['y'] = $chartValue / 100;
            $chartData[] = $data;
        }

        return response()->json($chartData);
    }

    public function sharePerformance($portfolioId): JsonResponse
    {
        $performance['day'] = 0;
        $performance['day_profit'] = 0;
        $performance['week'] = 0;
        $performance['week_profit'] = 0;
        $performance['month'] = 0;
        $performance['month_profit'] = 0;

        $startLastMonth = (new Carbon('first day of last month'))->format('Y-m-d');
        $endLastMonth = (new Carbon('last day of last month'))->format('Y-m-d');
        $startOfLastWeek = Carbon::now()->subDays(7)->startOfWeek()->format('Y-m-d');
        $endOfLastWeek = Carbon::now()->subDays(7)->endOfWeek()->previousWeekday()->format('Y-m-d');
        $avgLastMonth = Stock::portfolioId($portfolioId)
            ->whereBetween('stock_date', array($startLastMonth, $endLastMonth))
            ->avg('close');

        $avgLastWeek = Stock::portfolioId($portfolioId)
            ->whereBetween('stock_date', array($startOfLastWeek, $endOfLastWeek))
            ->avg('close');

        $todayPrev = Carbon::today();
        $lastWorkday = DateUtility::getLastWorkday($todayPrev);
        $prevDay = $lastWorkday->format('Y-m-d');
        $previousWeekdayModel = Stock::portfolioId($portfolioId)
            ->where('stock_date', $prevDay)
            ->first();
        $dayBeforePrevDay = $lastWorkday->previousWeekday()->format('Y-m-d');
        $dayBeforePreviousWeekday = Stock::portfolioId($portfolioId)
            ->where('stock_date', $dayBeforePrevDay)
            ->first();
        $last = Stock::portfolioId($portfolioId)
            ->orderBy('id', 'desc')
            ->first();
        $currentPerformance = StatisticService::calculateCurrentValues($last->close, $portfolioId);
        if ($currentPerformance === false) {
            return response()->json();
        }
        if ($previousWeekdayModel && $dayBeforePreviousWeekday) {
            $performance['day'] = $previousWeekdayModel->close - $dayBeforePreviousWeekday->close;
            $performance['day_profit'] = ($previousWeekdayModel->close - $dayBeforePreviousWeekday->close) * $currentPerformance['remainingShares'];
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

    public function active($portfolioId): JsonResponse
    {
        $price = Stock::where('portfolio_id', $portfolioId)->orderBy('id', 'desc')->first();
        $currentValues = 0;
        if ($price) {
            $currentValues = StatisticService::calculateCurrentValues($price->close, $price->portfolio_id);
        }

        return response()->json($currentValues);
    }

    public function archive($portfolioId): JsonResponse
    {
        $price = Stock::where('portfolio_id', $portfolioId)->orderBy('id', 'desc')->first();
        $currentValues = 0;
        if ($price) {
            $currentValues = StatisticService::calculatePastValues($price->close, $price->portfolio_id);
        }

        return response()->json($currentValues);
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
}
