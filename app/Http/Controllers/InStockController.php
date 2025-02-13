<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Stock;
use App\Services\StatisticService;
use Illuminate\Http\JsonResponse;
use Money\Currencies\ISOCurrencies;
use Money\Parser\IntlLocalizedDecimalParser;
use NumberFormatter;

class InStockController extends Controller
{
//https://flowbite.com/blog/how-to-use-flowbite-ui-components-with-laravel-and-alpine-js/
    public function shares(): JsonResponse
    {
        return response()->json(Portfolio::active()->select(['symbol', 'name'])->orderBy('symbol')->get());
    }

    public function details($symbol)
    {
        $price = Stock::where('symbol', $symbol)->orderBy('id', 'desc')->first();
        $currentValues = StatisticService::calculateCurrentValues($price->close, $symbol);

        return response()->json($currentValues);
    }
}
