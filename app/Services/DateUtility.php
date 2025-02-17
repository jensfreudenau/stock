<?php

namespace App\Services;

use Carbon\Carbon;

class DateUtility
{
    public static function getLastWorkday(Carbon $date): Carbon
    {
        $lastWorkday = $date->subDay();
        if ($lastWorkday->isSaturday()) {
            $lastWorkday->subDay(1);
        } elseif ($lastWorkday->isSunday()) {
            $lastWorkday->subDay(2);
        }

        return $lastWorkday;
    }
}
