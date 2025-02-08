<?php

namespace App\Models;

use Database\Factories\SellFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sell extends Model
{
    /** @use HasFactory<SellFactory> */
    use HasFactory;
    protected $fillable = [
        'symbol',
        'portfolio_id',
        'price_single',
        'revenue',
        'sales_proceeds',
        'amount',
        'sell_date'
    ];
    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}
