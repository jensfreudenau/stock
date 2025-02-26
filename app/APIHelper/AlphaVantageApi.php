<?php

namespace App\APIHelper;

use App\Models\Portfolio;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AlphaVantageApi extends ApiCall
{
    private string $symbol;
    private string $shareType;

    public function __construct($symbol, $shareType)
    {
        parent::__construct();
        $this->symbol = $symbol;
        $this->shareType = $shareType;
    }

    public function fillCompanyInfo(): false|array
    {
        $url = 'https://www.alphavantage.co/query?function=OVERVIEW&symbol=' . $this->symbol  . '&apikey=' .
            Config::get('app.alpha_vantage_key');
        $data = $this->call($url);
        if ($data === false) {
            return $this->fillCompanyInfoFromING();
        }
        if (empty($data) || array_key_exists('Information', $data)) {
            return $this->fillCompanyInfoFromING();
        }

        $portfolio['symbol'] = $this->symbol;
        $portfolio['name'] = $data['Name'];
        $portfolio['description'] = $data['Description'];
        $portfolio['exchange'] = $data['Exchange'];
        $portfolio['currency'] = $data['Currency'];
        $portfolio['country'] = $data['Country'];
        $portfolio['sector'] = $data['Sector'];
        $portfolio['site'] = $data['OfficialSite'];
        $portfolio['dividend_per_share'] = $data['DividendPerShare'];
        $portfolio['eps'] = $data['EPS'];
        $portfolio['analyst_target_price'] = $data['AnalystTargetPrice'];
        $portfolio['analyst_rating_strong_buy'] = $data['AnalystRatingStrongBuy'];
        $portfolio['analyst_rating_buy'] = $data['AnalystRatingBuy'];
        $portfolio['analyst_rating_hold'] = $data['AnalystRatingHold'];
        $portfolio['analyst_rating_sell'] = $data['AnalystRatingSell'];
        $portfolio['analyst_rating_strong_sell'] = $data['AnalystRatingStrongSell'];
        $portfolio['52_week_high'] = $data['52WeekHigh'];
        $portfolio['52_week_low'] = $data['52WeekLow'];

        return $portfolio;
    }

    public function fillHistory(string $isin, string $symbol): array
    {
        $url = 'https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=' . $symbol . '&interval=1min&apikey=' .
            Config::get('app.alpha_vantage_key');
        $data = $this->call($url);
        if (empty($data) || !array_key_exists('Time Series (Daily)', $data)) {
            Log::error('nothing found in :' . $url);
            return [];
        }
        $reverse = array_reverse($data['Time Series (Daily)'], true);
        $shareValue = [];
        foreach ($reverse as $key => $item) {
            $shareValue[$key]['symbol'] = $symbol;
            $shareValue[$key]['stock_date'] = $key;
            $shareValue[$key]['open'] = $item['1. open'];
            $shareValue[$key]['high'] = $item['2. high'];
            $shareValue[$key]['low'] = $item['3. low'];
            $shareValue[$key]['close'] = $item['4. close'];
            $shareValue[$key]['volume'] = $item['5. volume'];
        }
        return $shareValue;
    }

    private function fillCompanyInfoFromING(): array
    {
        $portfolio = Portfolio::where('symbol', $this->symbol)->first();
        $portfolioData = [];
        $urlAnalysis = 'https://component-api.wertpapiere.ing.de/api/v1/share/analysis/' . $portfolio->isin;
        $dataAnalysis = $this->call($urlAnalysis);

        if ($dataAnalysis === false) {
            $dataAnalysis['tableData'][0]['value'] = 1;
            $dataAnalysis['chartData'][1]['percent'] = 1;
            $dataAnalysis['chartData'][2]['percent'] = 1;
            $dataAnalysis['chartData'][0]['percent'] = 1;
        }

        $urlDescription = 'https://component-api.wertpapiere.ing.de/api/v1/components/description/' . $portfolio->isin;
        $dataDescription = $this->call($urlDescription);
        if ($dataDescription === false) {
            $dataDescription['description'] = '';
        }
        $urlInstrument = 'https://component-api.wertpapiere.ing.de/api/v1/components/instrumentheader/' . $portfolio->isin;
        $dataInstrument = $this->call($urlInstrument);
        if ($dataInstrument === false) {
            $dataInstrument['currency'] = '';
            $dataInstrument['stockMarket'] = '';
            $dataInstrument['name'] = '';
        }
        $urlReturnOfInvest = 'https://component-api.wertpapiere.ing.de/api/v1/share/returnofinvest/' . $portfolio->isin;
        $dataReturnOfInvest = $this->call($urlReturnOfInvest);
        if ($dataReturnOfInvest === false) {
            $dataReturnOfInvest['data'][0]['fieldValue']['value'] = 0;
            $dataReturnOfInvest['data'][1]['fieldValue']['value'] = '';
        }
        $urlFacts = 'https://component-api.wertpapiere.ing.de/api/v1/share/facts/' . $portfolio->isin;
        $dataFacts = $this->call($urlFacts);
        if ($dataFacts === false) {
            $dataFacts['data'][0] = ['value' => ''];
            $dataFacts['data'][1]['value'] = '';
            $dataFacts['companyLink'] = '';
        }
        $urlInformation = 'https://component-api.wertpapiere.ing.de/api/v1/components/priceinformation/' . $portfolio->isin;
        $dataInformation = $this->call($urlInformation);
        if ($dataInformation === false) {
            $dataInformation['data'][4]['fieldValue']['value'] = '';
            $dataInformation['data'][5]['fieldValue']['value'] = '';
        }

        $portfolioData['symbol'] = $this->symbol;
        if ($this->shareType === 'etf') {
            $urlFundInformation = 'https://component-api.wertpapiere.ing.de/api/v1/fund/strategy/' . $portfolio->isin;
            $dataFundInformationn = $this->call($urlFundInformation);
            $portfolioData['description'] = $dataFundInformationn['investmentStrategy'];
            $portfolioData['site'] = $dataAnalysis['detailUrl'];
            $portfolioData['analyst_rating_strong_sell'] = 0;
            $portfolioData['analyst_rating_strong_buy'] = 0;
            $portfolioData['analyst_rating_buy'] = 0;
            $portfolioData['analyst_rating_hold'] = 0;
            $portfolioData['analyst_rating_sell'] = 0;
        } else {
            $positive = 0;
            $neutral = 0;
            $negative = 0;
            foreach ($dataAnalysis['chartData'] as $chartData) {
                if (Str::contains($chartData['label'], 'Positiv')) {
                    $positive = $chartData['action'];
                }
                if (Str::contains($chartData['label'], 'Neutral')) {
                    $neutral = $chartData['action'];
                }
                if (Str::contains($chartData['label'], 'Negativ')) {
                    $negative = $chartData['action'];
                }
            }
            $portfolioData['site'] = $dataFacts['companyLink'];
            $portfolioData['analyst_target_price'] =
                Str::replace(',', '.', Str::before($dataAnalysis['tableData'][0]['value'], ' '));
            $portfolioData['description'] = $dataDescription['description'];
            $portfolioData['country'] = $dataFacts['data'][1]['value'];
            $portfolioData['sector'] = $dataFacts['data'][0]['value'];
            $portfolioData['dividend_per_share'] = $dataReturnOfInvest['data'][0]['fieldValue']['value'];
            $portfolioData['eps'] = $dataReturnOfInvest['data'][1]['fieldValue']['value'];
            $portfolioData['analyst_rating_strong_buy'] = 0;
            $portfolioData['analyst_rating_buy'] = $positive / 10;
            $portfolioData['analyst_rating_hold'] = $neutral / 10;
            $portfolioData['analyst_rating_sell'] = $negative / 10;
            $portfolioData['analyst_rating_strong_sell'] = 0;
            $portfolioData['52_week_low'] = $dataInformation['data'][4]['fieldValue']['value'];
            $portfolioData['52_week_high'] = $dataInformation['data'][5]['fieldValue']['value'];
        }

        $portfolioData['name'] = $dataInstrument['name'];
        $portfolioData['exchange'] = $dataInstrument['stockMarket'];
        $portfolioData['currency'] = $dataInstrument['currency'];
        return $portfolioData;
    }

}
