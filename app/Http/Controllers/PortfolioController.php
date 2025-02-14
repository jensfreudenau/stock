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
use Illuminate\Support\Facades\Log;

class PortfolioController extends Controller
{
    public function index($active): Application|Factory|View
    {
        return view('portfolio.index', compact('active'));
    }

    public function show($symbol)
    {
        return view('portfolio.show', compact('symbol'));
    }

    public function deactivate(Request $request): Application|Redirector|RedirectResponse
    {
        Portfolio::where('symbol', $request->symbol)->update(['active' => !$request->active]);
        return redirect('/portfolio/index/'.$request->active);
    }

    public function archive(): Application|Factory|View
    {
        return view('portfolio.archive');
    }

    public function activate(Request $request): Application|Redirector|RedirectResponse
    {
        Portfolio::where('symbol', $request->symbol)->update(['active' => $request->active]);
        Log::debug($request->active);
        return redirect('/portfolio/index');
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

    private function companyInfoRequest(string $symbol, string $shareType): false|array
    {
        if ($shareType === 'etf') {
            $shares = new FillShare($symbol, new ETFApi());
        } else {
            $shares = new FillShare($symbol, new AlphaVantageApi());
        }

        return $shares->fillCompanyInfo();
    }

    public function details($symbol): JsonResponse
    {
        return response()->json(Portfolio::active()->where('symbol', $symbol)->orderBy('symbol')->first());
    }

    public function portfolios($active): JsonResponse
    {
        return response()->json(Portfolio::active($active)->orderBy('symbol')->get());
    }

    public function activePortfolios(): JsonResponse
    {
        return response()->json(Portfolio::active()->orderBy('symbol')->get());
    }

    public function deactivePortfolios(): JsonResponse
    {
        return response()->json(Portfolio::active(false)->orderBy('symbol')->get());
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
        $portfolioInfo = $this->companyInfoRequest($request->symbol, $request->shareType);
        if ($portfolioInfo === false) {
            response()->json(['error' => 'Unable to fetch data'], 500);
            return redirect('/portfolio/index');
        }

        $portfolio = Portfolio::create($portfolioInfo);

        $histories = $this->historyRequest($request->symbol, $request->shareType);
        if ($histories === false) {
            response()->json(['error' => 'Unable to fetch data'], 500);
            return redirect('/portfolio/index');
        }
        foreach ($histories as $history) {
            $history['portfolio_id'] = $portfolio->id;
            Stock::create($history);
        }
        return redirect('/portfolio/index');
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
