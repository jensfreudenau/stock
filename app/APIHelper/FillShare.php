<?php

namespace App\APIHelper;

use App\APIHelper\Share;

class FillShare extends Share
{
    public string $symbol;

    public function __construct(string $symbol, ShareApi $shareApi)
    {
        parent::__construct($shareApi);
        $this->symbol = $symbol;
    }
    public function fillCompanyInfo(): array
    {
        return $this->shareApi->fillCompanyInfo($this->symbol);
    }

    public function fillHistory(): array
    {
        return $this->shareApi->fillHistory($this->symbol);
    }
}
