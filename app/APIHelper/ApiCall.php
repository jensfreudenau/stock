<?php

namespace App\APIHelper;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

abstract class ApiCall
{
    private Client $client;
    public function __construct()
    {
        $this->client = new Client();
    }

    public function call($url): array|false
    {
        try {
            $response = $this->client->request('GET', $url);
            $data = json_decode($response->getBody(), true);
            if (isset($data['Error Message'])) {
                Log::error($data['Error Message']);
                return false;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
        return $data;
    }
}
