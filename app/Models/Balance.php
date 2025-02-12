<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;
    protected $fillable = [
        'symbol',
        'portfolio_id',
        'diff',
        'buy_amount',
        'sell_amount',
        'total_amount',
        'price',
        'sales_proceeds',
        'avg_price',
        'deal_date',
        'balance',
    ];
}
