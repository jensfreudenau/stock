<?php

namespace App\APIHelper;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ETFApi extends ApiCall implements ShareInterface
{
    public function fillCompanyInfo($symbol): array
    {
        $data = $this->call('https://component-api.wertpapiere.ing.de/api/v1/fund/strategy/' . $symbol);
        $nameUrl = 'https://component-api.wertpapiere.ing.de/api/v1/components/pagemeta/' . $symbol . '?assetClass=Fund&isSubscriptionRight=false';
        $nameData = $this->call($nameUrl);
        $portfolio['description'] = $data['investmentStrategy'];
        $portfolio['symbol'] = $data['isin'];
        $portfolio['name'] = $nameData['pageTitle'];
        $portfolio['share_type'] = 'etf';
        $portfolio['active'] = 1;
        $portfolio['active_since'] = '1970-01-01';
        $portfolio['isin'] = $data['isin'];
        $portfolio['country'] = 'DE';

        return $portfolio;
    }

    public function fillHistory(string $isin, string $symbol): array
    {
        echo $url ='https://component-api.wertpapiere.ing.de/api/v1/charts/shm/' . $isin . '?timeRange=OneYear&exchangeId=2779&currencyId=814';
        $data = $this->call($url);
        if (empty($data) || !array_key_exists('instruments', $data)) {
            Log::error('nothing found in :' . $url);
            return [];
        }
        $shareValue = [];
        foreach ($data['instruments'][0]['data'] as $key => $item) {
            $shareValue[$key]['symbol'] = $symbol;
            $shareValue[$key]['isin'] = $isin;
            $shareValue[$key]['stock_date'] = Carbon::createFromTimestampMs($item[0])->format('Y-m-d');
            $shareValue[$key]['close'] = $item[1];
        }
        return $shareValue;
    }
}
