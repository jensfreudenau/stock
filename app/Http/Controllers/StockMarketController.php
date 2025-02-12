<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Stock;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;

class StockMarketController extends Controller
{
    private Client $client;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
    }

    public function initialStockData($symbol): JsonResponse
    {
        try {
            $url = 'https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=' . $symbol . '&interval=1min&apikey=' . env(
                    'ALPHA_VANTAGE_KEY'
                );

            $response = $this->client->request('GET', $url);
            $data = json_decode($response->getBody(), true);
            if (isset($data['Error Message'])) {
                return response()->json(['error' => 'Invalid stock symbol'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch data'], 500);
        }
        $reverse = array_reverse($data['Time Series (Daily)'], true);
        $portfolio = Portfolio::where('symbol', $symbol)->first();
        foreach ($reverse as $key => $item) {
            $item['symbol'] = $symbol;
            $item['portfolio_id'] = $portfolio->id;
            $item['stock_date'] = $key;
            $item['open'] = $item['1. open']*100;
            $item['high'] = $item['2. high']*100;
            $item['low'] = $item['3. low']*100;
            $item['close'] = $item['4. close']*100;
            $item['volume'] = $item['5. volume'];
            Stock::create($item);
        }

        return response()->json($data);
    }
}
