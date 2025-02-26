<?php

namespace App\APIHelper;

interface ShareInterface
{
    public function fillCompanyInfo(string $symbol);
    public function fillHistory(string $isin, string $symbol);
}
