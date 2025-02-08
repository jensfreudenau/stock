<?php

namespace App\Http\Controllers;

use App\Models\InStock;
use App\Models\Portfolio;
use App\Services\StatisticService;
use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Parser\IntlLocalizedDecimalParser;
use NumberFormatter;

class InStockController extends Controller
{
    private IntlLocalizedDecimalParser $moneyParser;

    public function __construct()
    {
        parent::__construct();
        $numberFormatter = new NumberFormatter('de_DE', NumberFormatter::DECIMAL);
        $this->moneyParser = new IntlLocalizedDecimalParser($numberFormatter, new ISOCurrencies());
    }

//https://flowbite.com/blog/how-to-use-flowbite-ui-components-with-laravel-and-alpine-js/
    public function add(Request $request): Application|JsonResponse|Redirector|RedirectResponse
    {
        $this->saveInStock($request, 'buy_at');
        return redirect('/balance/index');
    }

    public function shares(): JsonResponse
    {
        return response()->json(Portfolio::active()->select(['symbol', 'name'])->orderBy('symbol')->get());
    }

    public function store(Request $request): Application|Redirector|RedirectResponse
    {
        $this->saveInStock($request, 'buy_at');
        return redirect('/balance/index');
    }

    public function reduce(Request $request): Application|Redirector|RedirectResponse
    {
        $sellAt = $this->saveInStock($request, 'sell_at');
        $statisticService = new StatisticService();
        $statisticService->calculateDeal($request->symbol, $sellAt);
        $statisticService->save();
        return redirect('/balance/index');
    }

    private function parsePrice($price): \Money\Money
    {
        return $this->moneyParser->parse($price, new Currency('EUR'));
    }

    public function saveInStock(Request $request, $buyOrSell): string
    {
        $price = $this->parsePrice($request->price);
        $buyOrSellAt = Carbon::parse($request->$buyOrSell)->format('Y-m-d');
        $request->merge([$buyOrSell => $buyOrSellAt]);
        $request->merge(['price' => $price->getAmount()]);
        $request->merge(['amount' => Str::replace(',', '.', $request->amount)]);
        InStock::create($request->all());
        return $buyOrSellAt;
    }
}
