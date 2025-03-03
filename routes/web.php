<?php

use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\ScrapingController;
use App\Http\Controllers\StopLossController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\InStockController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatisticController;
use App\Http\Controllers\StockMarketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/stock', function () {
    return view('stock');
});
Route::get('/statistic', [StatisticController::class, 'index'])->name('statistic');
Route::get('/statistic/getShareSalesVolumeByYear/{year}', [StatisticController::class, 'getShareSalesVolumeByYear'])->name('statistic.getShareSalesVolumeByYear');
Route::get('/statistic/getProfitsByYear/{year}', [StatisticController::class, 'getProfitsByYear'])->name('statistic.getProfitsByYear');
Route::get('/statistic/chart/{symbol}', [StatisticController::class, 'chart'])->name('statistic.chart');
Route::get('/statistic/sharePerformance/{symbol}', [StatisticController::class, 'sharePerformance'])->name('statistic.sharePerformance');
Route::get('/statistic/active/{symbol}', [StatisticController::class, 'active'])->name('statistic.active');
Route::get('/statistic/archive/{symbol}', [StatisticController::class, 'archive'])->name('statistic.archive');

Route::get('instock/shares', [InStockController::class, 'shares'])->name('instock.shares');
Route::get('instock/details/{symbol}', [InStockController::class, 'details'])->name('instock.details');

Route::post('transaction/store', [TransactionController::class, 'store'])->name('transaction.store');
Route::post('transaction/add', [TransactionController::class, 'add'])->name('transaction.add');
Route::post('transaction/reduce', [TransactionController::class, 'reduce'])->name('transaction.reduce');
Route::get('/transaction/index', [TransactionController::class, 'index'])->name('transaction.index');
Route::get('/transaction/transactionsBySymbol/{symbol}', [TransactionController::class, 'transactionsBySymbol'])->name('transaction.transactionsBySymbol');
Route::get('/transaction', [TransactionController::class, 'transaction'])->name('transaction');

Route::get('/stock/{symbol}', [StockMarketController::class, 'initialStockData'])->name('initialStockData');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/portfolio/initial', [PortfolioController::class, 'initial'])->name('portfolio.initial');
Route::post('/portfolio/update', [PortfolioController::class, 'update'])->name('portfolio.update');
Route::post('/portfolio/deactivate', [PortfolioController::class, 'deactivate'])->name('portfolio.deactivate');
Route::get('/portfolio/index/{active}', [PortfolioController::class, 'index'])->name('indexPortfolio');
Route::get('/portfolio/archive', [PortfolioController::class, 'archive'])->name('portfolio.archive');
Route::get('/portfolio/analytics/{symbol}', [PortfolioController::class, 'analytics'])->name('analytics');
Route::get('/portfolio/show/{symbol}', [PortfolioController::class, 'show'])->name('portfolio.show');
Route::get('/portfolio/portfolios/{active}', [PortfolioController::class, 'portfolios'])->name('portfolio.portfolios');
Route::get('/portfolio/active_portfolios', [PortfolioController::class, 'activePortfolios'])->name('active_portfolios');
Route::get('/portfolio/deactive_portfolios', [PortfolioController::class, 'deactivePortfolios'])->name('deactive_portfolios');
Route::get('/portfolio/details/{symbol}', [PortfolioController::class, 'details'])->name('portfolio.details');

Route::get('/configuration/index',[ConfigurationController::class, 'index'])->name('configuration.index');
Route::post('/configuration/update',[ConfigurationController::class, 'update'])->name('configuration.update');
Route::post('/configuration/add', [ConfigurationController::class, 'add'])->name('configuration.add');
Route::post('/configuration/store', [ConfigurationController::class, 'store'])->name('configuration.store');
Route::delete('/configuration/{configuration}', [ConfigurationController::class, 'destroy'])->name('configuration.destroy');

Route::get('/stoploss/index',[StopLossController::class, 'index'])->name('stoploss.index');
Route::post('/stoploss/update',[StopLossController::class, 'update'])->name('stoploss.update');
Route::post('/stoploss/add', [StopLossController::class, 'add'])->name('stoploss.add');
Route::post('/stoploss/store', [StopLossController::class, 'store'])->name('stoploss.store');
Route::delete('/stoploss/{stoploss}', [StopLossController::class, 'destroy'])->name('stoploss.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
