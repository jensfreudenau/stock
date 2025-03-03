<?php

namespace App\APIHelper;

use App\DTOs\GeneralDTO;
use App\Models\Portfolio;

class FillShare extends ShareAbstract
{
    public Portfolio $portfolio;

    public function __construct(Portfolio $portfolio, ShareInterface $shareApi)
    {
        parent::__construct($shareApi);
        $this->portfolio = $portfolio;
    }
    public function fillCompanyInfo(): false|array
    {
        return $this->shareApi->fillCompanyInfo($this->portfolio);
    }

    public function fillHistory(): false|array
    {
        return $this->shareApi->fillHistory($this->portfolio);
    }

    public function fillCurrent(): false|GeneralDTO
    {
        return $this->shareApi->fillCurrent($this->portfolio);
    }
}
