<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    protected $fillable = [
        'portfolio_id',
        'symbol',
        'name',
        'description',
        'country',
        'exchange',
        'currency',
        'country',
        'sector',
        'site',
        'dividend_per_share',
        'eps',
        'analyst_target_price',
        'analyst_rating_strong_sell',
        'analyst_rating_strong_buy',
        'analyst_rating_buy',
        'analyst_rating_sell',
        'analyst_rating_hold',
        '52_week_high',
        '52_week_low',
    ];

    public function portfolio(): HasOne
    {
        return $this->hasOne(Portfolio::class);
    }
}
