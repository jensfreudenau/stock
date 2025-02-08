<?php

namespace App\APIHelper;

use Carbon\Carbon;

class ETFApi extends ApiCall implements shareApi
{
    public function fillShare(string $symbol): array
    {
        $data = $this->call('https://component-api.wertpapiere.ing.de/api/v1/fund/strategy/IE00BYX2JD69');
        $nameUrl = 'https://component-api.wertpapiere.ing.de/api/v1/components/pagemeta/IE00BYX2JD69?assetClass=Fund&isSubscriptionRight=false';
        $nameData = $this->call($nameUrl);
        $portfolio['description'] = $data['investmentStrategy'];
        $portfolio['symbol'] = $data['isin'];
        $portfolio['name'] = $nameData['pageTitle'];
        $portfolio['share_type'] = 'etf';
        $portfolio['active'] = 1;
        $portfolio['isin'] = $data['isin'];
        $portfolio['country'] = 'DE';

        return $portfolio;
    }

    public function fillHistory(): array
    {
        $historyUrl = 'https://component-api.wertpapiere.ing.de/api/v1/components/charttooldata/37665525?timeRange=OneYear&exchangeId=2779&currencyId=814';
        $historyData = $this->call($historyUrl);
        $data= [];
        foreach ($historyData['instruments'][0]['data'] as $key =>  $item) {
            $data[$key]['stock_date'] = Carbon::createFromTimestampMs($item[0], 'Europe/Berlin')->format('Y-m-d');
            $data[$key]['open'] = $item[1];
            $data[$key]['close'] = $item[1];
            $data[$key]['low'] = $item[1];
            $data[$key]['high'] = $item[1];
            $data[$key]['volume'] = 0;
            $data[$key]['symbol'] = 'IE00BYX2JD69';
            $data[$key]['isin'] = 'IE00BYX2JD69';
        }
        return $data;
    }
}
