<?php

namespace App\Http\Controllers;

use App\APIHelper\AlphaVantageApi;
use App\APIHelper\ETFApi;
use App\APIHelper\FillShare;
use App\APIHelper\SharesApi;
use App\Models\Company;
use App\Models\Portfolio;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class PortfolioController extends Controller
{
    public function index($active): Application|Factory|View
    {
        $portfolios = Portfolio::active($active)->orderBy('id')->get();
        return view('portfolio.index', compact('active', 'portfolios'));
    }

    public function show($id): Application|Factory|View
    {
        $portfolio = Portfolio::where('id', $id)->first();
        return view('portfolio.show', compact('portfolio'));
    }

    public function deactivate(Request $request): Application|Redirector|RedirectResponse
    {
        Portfolio::where('symbol', $request->symbol)->update(['active' => !$request->active]);
        return redirect('/portfolio/index/' . $request->active);
    }

    public function archive(): Application|Factory|View
    {
        return view('portfolio.archive');
    }

    public function activate(Request $request): Application|Redirector|RedirectResponse
    {
        Portfolio::where('id', $request->id)->update(['active' => $request->active]);
        return redirect('/portfolio/index/1');
    }

    public function update(Request $request): Application|Redirector|RedirectResponse
    {
        $portfolio = Portfolio::where('id', $request->id)->first();
        $portfolioData = $this->setCompanyInfo($portfolio);

        if ($portfolioData === false) {
            response()->json(['error' => 'Unable to fetch data'], 500);
            return redirect('/portfolio/index');
        }
        $company = Company::where('portfolio_id', $portfolio->id);
        $company->update($portfolioData);

        return redirect('/portfolio/index/1');
    }

    private function companyInfoRequest(Portfolio $portfolio, string $shareType): false|array
    {
        if ($shareType === 'etf') {
            $shares = new FillShare($portfolio, new ETFApi());
        } else {
            $shares = new FillShare($portfolio, new SharesApi());
        }

        return $shares->fillCompanyInfo();
    }

    public function details($id): JsonResponse
    {
        return response()->json(Portfolio::getInfoWithCompany($id));
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
        return response()->json(Portfolio::active(0)->orderBy('symbol')->get());
    }

    public function analytics($id): JsonResponse
    {
        return response()->json(Portfolio::getInfoWithCompany($id));
    }

    public function initial(Request $request): Application|Redirector|RedirectResponse
    {
        $symbol = $request->symbol;
        if ($request->share_type === 'share') {
            $symbol = $request->isin;;
        }
        $portfolioInfo = $this->companyInfoRequest($symbol, $request->isin, $request->share_type);
        if ($portfolioInfo === false) {
            response()->json(['error' => 'Unable to fetch data'], 500);
            return redirect('/portfolio/index');
        }
        $portfolioInfo['share_type'] = $request->share_type;
        $portfolioInfo['currency'] = $request->currency;
        $portfolioInfo['active'] = $request->active;
        $portfolioInfo['isin'] = $request->isin;
        $portfolioInfo['symbol'] = $request->symbol;
        $portfolioInfo['active_since'] = (new Carbon($request->active_since))->format('Y-m-d');
        $portfolio = Portfolio::create($portfolioInfo);

        $histories = $this->historyRequest($portfolio, $request->share_type);
        if ($histories === false) {
            response()->json(['error' => 'Unable to fetch data'], 500);
            return redirect('/portfolio/index');
        }
        foreach ($histories as $history) {
            $history['portfolio_id'] = $portfolio->id;
            Stock::create($history);
        }
        $companyInfo = $this->setCompanyInfo($portfolio);
        Company::create($companyInfo);

        return redirect('/transaction/index');
    }


    private function setCompanyInfo($portfolio): false|array
    {
        if($portfolio->share_type === 'etf') {
            $companyInfo = (new ETFApi())->fillCompanyInfo($portfolio);
        } else {
            $companyInfo = (new AlphaVantageApi())->fillCompanyInfo($portfolio);
        }

        if ($companyInfo) {
            $companyInfo['portfolio_id'] = $portfolio->id;
            return $companyInfo;
        }
        return false;
    }


    private function historyRequest($portfolio, string $shareType): false|array
    {
        if ($shareType === 'etf') {
            $shares = new FillShare($portfolio, new ETFApi());
        } else {
            $shares = new FillShare($portfolio, new SharesApi());
        }

        return $shares->fillHistory();
    }
}
