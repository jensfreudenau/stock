<?php

namespace App\APIHelper;

use App\APIHelper\shareApi;
use App\Models\Stock;

class AlphaVantageApi extends ApiCall implements shareApi
{

    public function fillShare(string $symbol): array
    {
        $url = 'https://www.alphavantage.co/query?function=OVERVIEW&symbol=' . $symbol . '&apikey=' . env('ALPHA_VANTAGE_KEY');
        $data = $this->call($url);
        $portfolio = [];
        $portfolio['symbol'] = $data['Symbol'];
        $portfolio['name'] = $data['Name'];
        $portfolio['description'] = $data['Description'];
        $portfolio['country'] = $data['Country'];
        $portfolio['sector'] = $data['Sector'];
        $portfolio['analyst_rating_strong_sell'] = $data['AnalystRatingStrongSell'];
        $portfolio['analyst_rating_strong_buy'] = $data['AnalystRatingStrongBuy'];
        $portfolio['analyst_rating_sell'] = $data['AnalystRatingSell'];
        $portfolio['analyst_rating_hold'] = $data['AnalystRatingHold'];

        return $portfolio;
    }

    public function fillHistory(string $symbol): array
    {
        $url = 'https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol='.$symbol.'&interval=1min&apikey='.env('ALPHA_VANTAGE_KEY');
        $data = $this->call($url);
        $reverse = array_reverse($data['Time Series (Daily)'], true);
        $item = [];
        foreach ($reverse as $key => $item) {
            $item[$key]['symbol'] = $symbol;
            $item[$key]['stock_date'] = $key;
            $item[$key]['open'] = $item['1. open'];
            $item[$key]['high'] = $item['2. high'];
            $item[$key]['low'] = $item['3. low'];
            $item[$key]['close'] = $item['4. close'];
            $item[$key]['volume'] = $item['5. volume'];
        }
        return $item;
    }
}
