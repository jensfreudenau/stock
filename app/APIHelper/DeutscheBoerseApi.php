<?php

namespace App\APIHelper;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DeutscheBoerseApi extends ApiCall implements ShareApi
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

    public function fillHistory($symbol): array
    {
        #FR0010717090
        $historyUrl = 'https://api.boerse-frankfurt.de/v1/data/price_information/single?isin=' . $symbol . '&mic=XFRA';
        $historyData = $this->call($historyUrl);
        $data = [];
        if (empty($historyData)) {
            Log::error('nothing found in :' . $historyUrl);
            return [];
        }

        $data['stock_date'] = Carbon::parse($historyData['timestampLastPrice'], 'Europe/Berlin')->format('Y-m-d');
        $data['open'] = $historyData['lastPrice'];
        $data['close'] = $historyData['lastPrice'];
        $data['low'] = $historyData['lastPrice'];
        $data['high'] = $historyData['lastPrice'];
        $data['volume'] = 0;
        $data['symbol'] = $symbol;
        $data['isin'] = $symbol;

        return $data;
    }
}
