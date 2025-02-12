<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'symbol',
        'portfolio_id',
        'type',
        'quantity',
        'buy_quantity',
        'price',
        'transaction_at',
    ];
    protected $casts = [
        'transaction_at' => 'date:d.m.Y'
    ];
    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
    public static function getAllShares()
    {
        return InStock::select('symbol')->groupBy('symbol')->orderBy('symbol', 'asc')->get();
    }
}
