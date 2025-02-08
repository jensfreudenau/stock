<?php

use App\Http\Controllers\BalanceController;
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
Route::get('/balance/index', [BalanceController::class, 'index'])->name('balance.index');
Route::get('/statistic', [StatisticController::class, 'index'])->name('statistic');
Route::get('/statistic/getShareSalesVolumeByYear/{year}', [StatisticController::class, 'getShareSalesVolumeByYear'])->name('statistic.getShareSalesVolumeByYear');


Route::post('instock/store', [InStockController::class, 'store'])->name('instock.store');
Route::post('instock/add', [InStockController::class, 'add'])->name('instock.add');
Route::post('instock/reduce', [InStockController::class, 'reduce'])->name('instock.reduce');
Route::get('instock/shares', [InStockController::class, 'shares'])->name('instock.shares');
Route::get('/balance', [BalanceController::class, 'balance'])->name('balance');
Route::get('/stock/{symbol}', [StockMarketController::class, 'initialStockData'])->name('initialStockData');
Route::get('/statistic/charts', [StatisticController::class, 'charts'])->name('statistic.charts');
Route::get('/statistic/chart/{symbol}', [StatisticController::class, 'chart'])->name('statistic.chart');
Route::get('/statistic/sharePerformance/{symbol}', [StatisticController::class, 'sharePerformance'])->name('statistic.sharePerformance');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/portfolio/initial', [PortfolioController::class, 'initial'])->name('portfolio.initial');
Route::post('/portfolio/update', [PortfolioController::class, 'update'])->name('portfolio.update');
Route::post('/portfolio/deactivate', [PortfolioController::class, 'deactivate'])->name('portfolio.deactivate');
Route::get('/portfolio/index', [PortfolioController::class, 'index'])->name('indexPortfolio');
Route::get('/portfolio/analytics/{symbol}', [PortfolioController::class, 'analytics'])->name('analytics');
Route::get('/portfolio/active_portfolios', [PortfolioController::class, 'activePortfolios'])->name('active_portfolios');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
