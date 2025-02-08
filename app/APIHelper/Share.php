<?php

namespace App\APIHelper;

abstract class Share
{
    protected ShareApi $shareApi;

    protected function __construct(ShareApi $shareAPI) {
        $this->shareApi = $shareAPI;
    }

    public abstract function fillCompanyInfo();
    public abstract function fillHistory();
}
