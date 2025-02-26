<?php

namespace App\APIHelper;

abstract class Share
{
    protected ShareInterface $shareApi;

    protected function __construct(ShareInterface $shareAPI) {
        $this->shareApi = $shareAPI;
    }

    public abstract function fillCompanyInfo();
    public abstract function fillHistory();
}
