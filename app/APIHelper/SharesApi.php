<?php

namespace App\APIHelper;

use App\DTOs\GeneralDTO;
use App\DTOs\IngDTO;
use App\DTOs\Mappers\DTOMapper;
use App\Models\Portfolio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SharesApi extends ApiCall implements ShareInterface
{
    public function fillCompanyInfo(Portfolio $portfolio): false|array
    {
        $url = 'https://component-api.wertpapiere.ing.de/api/v1/components/instrumentheader/' . $portfolio->isin;
        $data = $this->call($url);
        if ($data === false) {
            return false;
        }
        $urlDescription = 'https://component-api.wertpapiere.ing.de/api/v1/components/description/' . $portfolio->isin;
        $dataDescription = $this->call($urlDescription);
        if ($dataDescription === false) {
            $dataDescription = ['description' => ''];
        }
        $portfolio = [];
        $portfolio['symbol'] = $portfolio->symbol;
        $portfolio['isin'] = $data['isin'];
        $portfolio['name'] = $data['name'];
        $portfolio['ing_id'] = $data['id'];
        $portfolio['wkn'] = $data['wkn'];
        $portfolio['internal_isin'] = $data['internalIsin'];
        $portfolio['description'] = $dataDescription['description'];
        $portfolio['active'] = 1;

        return $portfolio;
    }

    public function fillHistory(Portfolio $portfolio): array
    {
        $url = 'https://component-api.wertpapiere.ing.de/api/v1/charts/shm/' . $portfolio->isin . '?timeRange=OneYear&exchangeId=2779&currencyId=814';
        $data = $this->call($url);
        if (empty($data) || !array_key_exists('instruments', $data)) {
            Log::error('nothing found in :' . $url);
            return [];
        }
        $shareValues = [];
        foreach ($data['instruments'][0]['data'] as $item) {
            $ingData = [
                'item_0' => Carbon::createFromTimestampMs($item[0])->format('Y-m-d'),
                'item_1' => $item[1] * 100,
            ];
            $ingDTO = IngDTO::fromArray($ingData);
            $shareValues[] = DTOMapper::mapToGeneralDTO($ingDTO, $portfolio);
        }

        return $shareValues;
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
        $ingData = [
            'item_0' => Carbon::createFromTimestampMs($currentValue[0])->format('Y-m-d'),
            'item_1' => $currentValue[1] * 100,
        ];
        $ingDTO = IngDTO::fromArray($ingData);
        return DTOMapper::mapToGeneralDTO($ingDTO, $portfolio);
    }
}
