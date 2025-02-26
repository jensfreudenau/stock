<?php

namespace App\APIHelper;

class FillShare extends Share
{
    public string $symbol;
    public string $isin;

    public function __construct(string $symbol,string $isin, ShareInterface $shareApi)
    {
        parent::__construct($shareApi);
        $this->symbol = $symbol;
        $this->isin = $isin;
    }
    public function fillCompanyInfo(): false|array
    {
        return $this->shareApi->fillCompanyInfo($this->symbol, $this->isin);
    }

    public function fillHistory(): false|array
    {
        return $this->shareApi->fillHistory($this->symbol, $this->isin);
    }
}
