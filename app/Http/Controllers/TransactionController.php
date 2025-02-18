<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Transaction;
use App\Services\StatisticService;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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

class TransactionController extends Controller
{
    private IntlLocalizedDecimalParser $moneyParser;

    public function __construct()
    {
        parent::__construct();
        $numberFormatter = new NumberFormatter('de_DE', NumberFormatter::DECIMAL);
        $this->moneyParser = new IntlLocalizedDecimalParser($numberFormatter, new ISOCurrencies());
    }

    public function index(): View|Factory|Application
    {
        return view('transaction.index');
    }

    public function transaction(): JsonResponse
    {
        $transactions = Transaction::orderBy('symbol', 'asc')->orderBy('transaction_at', 'desc')->get();
        return response()->json($transactions);
    }

    public function transactionsBySymbol($symbol): JsonResponse
    {
        $transactions = Transaction::where('symbol', $symbol)->orderBy('transaction_at', 'desc')->get();

        return response()->json($transactions);
    }

    public function add(Request $request): Application|JsonResponse|Redirector|RedirectResponse
    {
        $this->saveInStock($request);
        return redirect('/transaction/index');
    }

    public function store(Request $request): Application|Redirector|RedirectResponse
    {
        $this->saveInStock($request);
        return redirect('/transaction/index');
    }

    public function reduce(Request $request): Application|Redirector|RedirectResponse
    {
        $sellId = $this->saveInStock($request);
        StatisticService::calculateProfitForSell($sellId, $request->transaction_at);
        return redirect('/transaction/index');
    }

    private function saveInStock(Request $request)
    {
        $portfolio = Portfolio::where('symbol', $request->symbol)->first();
        $allPrice = $this->parsePrice($request->all_price);
        $buyOrSellAt = Carbon::parse($request->transaction_at)->format('Y-m-d');
        $quantity = Str::replace(',', '.', $request->quantity);
        $request->merge(['transaction_at' => $buyOrSellAt]);
        $request->merge(['portfolio_id' => $portfolio->id]);
        $request->merge(['price' => $allPrice->getAmount() / $quantity]);
        $request->merge(['quantity' => $quantity]);
        $request->merge(['buy_quantity' => Str::replace(',', '.', $request->quantity)]);
        $transaction = Transaction::create($request->all());
        return $transaction->id;
    }

    private function parsePrice($price): \Money\Money
    {
        return $this->moneyParser->parse($price, new Currency('EUR'));
    }
}
