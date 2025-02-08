<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class InStock extends Model
{
    use HasFactory;
    protected $fillable = [
        'symbol',
        'portfolio_id',
        'name',
        'amount',
        'price',
        'buy_at',
        'sell_price',
        'sell_at',
        'sell_amount',
    ];
    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
    protected $casts = [
        'sell_at' => 'date:d.m.Y',
        'buy_at' => 'date:d.m.Y',
    ];

    public static function getAllShares()
    {
        return InStock::select('symbol')->groupBy('symbol')->orderBy('symbol', 'asc')->get();
    }
}
