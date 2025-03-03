<?php

namespace App\APIHelper;

use App\DTOs\GeneralDTO;
use App\Models\Portfolio;

interface ShareInterface
{
    public function fillCompanyInfo(Portfolio $portfolio): false|array;
    public function fillHistory(Portfolio $portfolio): false|array;
    public function fillCurrent(Portfolio $portfolio): false|GeneralDTO;
}
