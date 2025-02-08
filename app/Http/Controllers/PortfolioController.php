<?php

namespace App\Http\Controllers;

use App\APIHelper\AlphaVantageApi;
use App\APIHelper\ETFApi;
use App\APIHelper\FillShare;
use App\Models\Portfolio;
use App\Models\Stock;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;

class PortfolioController extends Controller
{
    public function index(): Application|Factory|View
    {
        return view('portfolio.index');
    }

    public function update(Request $request): Application|Redirector|RedirectResponse
    {
        $portfolio = Portfolio::where('symbol', $request->symbol)->first();
        $portfolioData = $this->companyInfoRequest($request->symbol, $portfolio->share_type);
        if ($portfolioData === false) {
            response()->json(['error' => 'Unable to fetch data'], 500);
            return redirect('/portfolio/index');
        }
        Portfolio::where('symbol', $request->symbol)
            ->update($portfolioData);

        return redirect('/portfolio/index');
    }

    public function deactivate(Request $request): Application|Redirector|RedirectResponse
    {
        Portfolio::where('symbol', $request->symbol)->update(['active' => !$request->active]);
        return redirect('/portfolio/index');
    }

    public function activePortfolios(): JsonResponse
    {
        return response()->json(Portfolio::active()->orderBy('symbol')->get());
    }

    public function analytics($symbol): JsonResponse
    {
        $portfolio = Portfolio::query()
            ->where('symbol', $symbol)
            ->get();
        return response()->json($portfolio);
    }

    public function initial(Request $request): Application|Redirector|RedirectResponse
    {
        $portfolio = $this->companyInfoRequest($request->symbol, $request->shareType);
        if ($portfolio === false) {
            response()->json(['error' => 'Unable to fetch data'], 500);
            return redirect('/portfolio/index');
        }
        Portfolio::create($portfolio);

        $histories = $this->historyRequest($request->symbol, $request->shareType);
        if ($histories === false) {
            response()->json(['error' => 'Unable to fetch data'], 500);
            return redirect('/portfolio/index');
        }
        foreach ($histories as $history) {
            Stock::create($history);
        }
        return redirect('/portfolio/index');
    }

    private function companyInfoRequest(string $symbol, string $shareType): false|array
    {
        if ($shareType === 'etf') {
            $shares = new FillShare($symbol, new ETFApi());
        } else {
            $shares = new FillShare($symbol, new AlphaVantageApi());
        }

        return $shares->fillCompanyInfo();
    }

    private function historyRequest(string $symbol, string $shareType): false|array
    {
        if ($shareType === 'etf') {
            $shares = new FillShare($symbol, new ETFApi());
        } else {
            $shares = new FillShare($symbol, new AlphaVantageApi());
        }

        return $shares->fillHistory();
    }
}
