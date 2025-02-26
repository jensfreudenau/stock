<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function scopeSells(Builder $query, $portfolioId)
    {
        $query->where('type', 'sell')
            ->where('portfolio_id', $portfolioId)
            ->orderBy('transaction_at');
    }
    public function scopeBuys(Builder $query, $portfolioId)
    {
        $query->where('type', 'buy')
            ->where('portfolio_id', $portfolioId)
            ->orderBy('transaction_at');
    }
}
