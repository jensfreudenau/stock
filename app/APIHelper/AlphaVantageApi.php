<?php

namespace App\APIHelper;

use App\DTOs\GeneralDTO;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class AlphaVantageApi extends ApiCall implements ShareInterface
{
    public function fillCompanyInfo(Portfolio $portfolio): false|array
    {
        $url = 'https://www.alphavantage.co/query?function=OVERVIEW&symbol=' . $portfolio->symbol  . '&apikey=' .
            Config::get('app.alpha_vantage_key');
        $data = $this->call($url);
//        if ($data === false) {
//            return $this->fillCompanyInfoFromING();
//        }
//        if (empty($data) || array_key_exists('Information', $data)) {
//            return $this->fillCompanyInfoFromING();
//        }

        $portfolioData['symbol'] = $portfolio->symbol;
        $portfolioData['name'] = $data['Name'];
        $portfolioData['description'] = $data['Description'];
        $portfolioData['exchange'] = $data['Exchange'];
        $portfolioData['currency'] = $data['Currency'];
        $portfolioData['country'] = $data['Country'];
        $portfolioData['sector'] = $data['Sector'];
        $portfolioData['site'] = $data['OfficialSite'];
        $portfolioData['dividend_per_share'] = $data['DividendPerShare'];
        $portfolioData['eps'] = $data['EPS'];
        $portfolioData['analyst_target_price'] = $data['AnalystTargetPrice'];
        $portfolioData['analyst_rating_strong_buy'] = $data['AnalystRatingStrongBuy'];
        $portfolioData['analyst_rating_buy'] = $data['AnalystRatingBuy'];
        $portfolioData['analyst_rating_hold'] = $data['AnalystRatingHold'];
        $portfolioData['analyst_rating_sell'] = $data['AnalystRatingSell'];
        $portfolioData['analyst_rating_strong_sell'] = $data['AnalystRatingStrongSell'];
        $portfolioData['52_week_high'] = $data['52WeekHigh'];
        $portfolioData['52_week_low'] = $data['52WeekLow'];

        return $portfolioData;
    }

    public function fillHistory(Portfolio $portfolio): false|array
    {
        $url = 'https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=' . $portfolio->symbol . '&interval=1min&apikey=' .
            Config::get('app.alpha_vantage_key');
        $data = $this->call($url);
        if (empty($data) || !array_key_exists('Time Series (Daily)', $data)) {
            Log::error('nothing found in :' . $url);
            return false;
        }
        $reverse = array_reverse($data['Time Series (Daily)'], true);
        $shareValue = [];
        foreach ($reverse as $key => $item) {
            $shareValue[$key]['symbol'] = $portfolio->symbol;
            $shareValue[$key]['stock_date'] = $key;
            $shareValue[$key]['open'] = $item['1. open'];
            $shareValue[$key]['high'] = $item['2. high'];
            $shareValue[$key]['low'] = $item['3. low'];
            $shareValue[$key]['close'] = $item['4. close'];
            $shareValue[$key]['volume'] = $item['5. volume'];
        }
        return $shareValue;
    }

    public function fillCurrent(Portfolio $portfolio): false|GeneralDTO
    {
        if(empty($portfolio)) {
            return false;
        }
        return[];
    }



}
