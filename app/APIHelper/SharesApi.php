<?php

namespace App\APIHelper;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SharesApi extends ApiCall implements ShareInterface
{
    public function fillCompanyInfo(string $symbol): false|array
    {
        $url = 'https://component-api.wertpapiere.ing.de/api/v1/components/instrumentheader/' . $symbol;
        $data = $this->call($url);
        if ($data === false) {
            return false;
        }
        $urlDescription ='https://component-api.wertpapiere.ing.de/api/v1/components/description/' . $symbol;
        $dataDescription = $this->call($urlDescription);
        if ($dataDescription === false) {
            $dataDescription = ['description' => ''];
        }
        $portfolio = [];
        $portfolio['symbol'] = $symbol;
        $portfolio['isin'] = $data['isin'];
        $portfolio['name'] = $data['name'];
        $portfolio['ing_id'] = $data['id'];
        $portfolio['wkn'] = $data['wkn'];
        $portfolio['internal_isin'] = $data['internalIsin'];
        $portfolio['description'] = $dataDescription['description'];
        $portfolio['active'] = 1;

        return $portfolio;
    }

    public function fillHistory(string $isin, string $symbol): array
    {
        $url ='https://component-api.wertpapiere.ing.de/api/v1/charts/shm/' . $isin . '?timeRange=OneYear&exchangeId=2779&currencyId=814';
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
