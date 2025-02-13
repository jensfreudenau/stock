<?php

namespace App\APIHelper;

use App\APIHelper\shareApi;
use App\Models\Stock;
use Illuminate\Support\Facades\Log;

class AlphaVantageApi extends ApiCall implements shareApi
{
    public function fillCompanyInfo($symbol): array
    {
        $url = 'https://www.alphavantage.co/query?function=OVERVIEW&symbol=' . $symbol . '&apikey=' . env('ALPHA_VANTAGE_KEY');
        $data = $this->call($url);
        if(array_key_exists('Information', $data)) return [];
        $portfolio = [];
        $portfolio['symbol'] = $symbol;
        $portfolio['name'] = $data['Name'];
        $portfolio['description'] = $data['Description'];
        $portfolio['country'] = $data['Country'];
        $portfolio['sector'] = $data['Sector'];
        $portfolio['active'] = $data['active'];
        $portfolio['active_since'] = $data['active_since'];
        $portfolio['analyst_rating_strong_sell'] = $data['AnalystRatingStrongSell'];
        $portfolio['analyst_rating_strong_buy'] = $data['AnalystRatingStrongBuy'];
        $portfolio['analyst_rating_sell'] = $data['AnalystRatingSell'];
        $portfolio['analyst_rating_hold'] = $data['AnalystRatingHold'];

        return $portfolio;
    }

    public function fillHistory($symbol): array
    {
        $url = 'https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol='.$symbol.'&interval=1min&apikey='.env('ALPHA_VANTAGE_KEY');
        $data = $this->call($url);
        if(empty($data) || !array_key_exists('Time Series (Daily)', $data)) {
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
}
