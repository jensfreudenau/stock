<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Money\Money;

class Stock extends Model
{
    protected $fillable = [
        'symbol',
        'portfolio_id',
        'isin',
        'stock_date',
        'open',
        'low',
        'high',
        'close',
        'volume',
    ];
    protected $casts = [
        'stock_date' => 'date:d.m.Y',
        'price' => Money::class
    ];
    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}
