<?php

namespace App\APIHelper;

use App\DTOs\EtfDTO;
use App\DTOs\GeneralDTO;
use App\DTOs\IngDTO;
use App\DTOs\Mappers\DTOMapper;
use App\Models\Portfolio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ETFApi extends ApiCall implements ShareInterface
{
    public function fillCompanyInfo(Portfolio $portfolio): false|array
    {
        $data = $this->call('https://component-api.wertpapiere.ing.de/api/v1/fund/strategy/' . $portfolio->symbol);
        $nameUrl = 'https://component-api.wertpapiere.ing.de/api/v1/components/pagemeta/' . $portfolio->symbol . '?assetClass=Fund&isSubscriptionRight=false';
        $nameData = $this->call($nameUrl);
        if (empty($nameData)) {
            return false;
        }
        $portfolioData['description'] = $data['investmentStrategy'];
        $portfolioData['symbol'] = $data['isin'];
        $portfolioData['name'] = $nameData['pageTitle'];
        $portfolioData['share_type'] = 'etf';
        $portfolioData['active'] = 1;
        $portfolioData['active_since'] = '1970-01-01';
        $portfolioData['isin'] = $data['isin'];
        $portfolioData['country'] = 'DE';

        return $portfolioData;
    }

    public function fillHistory(Portfolio $portfolio): false|array
    {
        $url = 'https://component-api.wertpapiere.ing.de/api/v1/charts/shm/' . $portfolio->isin . '?timeRange=OneYear&exchangeId=2779&currencyId=814';
        $data = $this->call($url);
        if (empty($data) || !array_key_exists('instruments', $data)) {
            Log::error('nothing found in :' . $url);
            return [];
        }
        $etfValues = [];
        foreach ($data['instruments'][0]['data'] as $item) {
            $etfDTO = [
                'item_0' => Carbon::createFromTimestampMs($item[0])->format('Y-m-d'),
                'item_1' => $item[1] * 100,
            ];
            $etfDTO = EtfDTO::fromArray($etfDTO);
            $etfValues[] = DTOMapper::mapToGeneralDTO($etfDTO, $portfolio);
        }

        return $etfValues;
    }

    public function fillCurrent(Portfolio $portfolio): GeneralDTO|false
    {
        $url = 'https://component-api.wertpapiere.ing.de/api/v1/charts/shm/' . $portfolio->isin . '?timeRange=Intraday&exchangeId=2779&currencyId=814';
        $data = $this->call($url);
        if (empty($data) || !array_key_exists('instruments', $data)) {
            Log::error('nothing found in :' . $url);
            return false;
        }
        $shareValues = $data['instruments'][0]['data'];
        $currentValue = end($shareValues);
        $etfData = [
            'item_0' => Carbon::createFromTimestampMs($currentValue[0])->format('Y-m-d'),
            'item_1' => $currentValue[1] * 100,
        ];
        $etfDTO = EtfDTO::fromArray($etfData);
        return DTOMapper::mapToGeneralDTO($etfDTO, $portfolio);
    }
}
