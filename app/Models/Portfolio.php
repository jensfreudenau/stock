<?php

namespace App\Models;

use Database\Factories\PortfolioFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portfolio extends Model
{
    /** @use HasFactory<PortfolioFactory> */
    use HasFactory;

    protected $fillable = [
        'symbol',
        'name',
        'description',
        'country',
        'share_type',
        'isin',
        'active',
        'active_since',
        'sector',
        'analyst_rating_strong_sell',
        'analyst_rating_strong_buy',
        'analyst_rating_sell',
        'analyst_rating_hold'
    ];

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function sells()
    {
        return $this->hasMany(Sell::class);
    }

    public function scopeActive(Builder $query, $active = 1): void
    {
        $query->where('active', $active);
    }
}
