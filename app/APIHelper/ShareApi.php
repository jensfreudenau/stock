<?php

namespace App\APIHelper;

interface ShareApi
{
    public function fillCompanyInfo(string $symbol);
    public function fillHistory(string $symbol);
}
