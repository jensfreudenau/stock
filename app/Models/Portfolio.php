<?php

namespace App\Models;

use Database\Factories\PortfolioFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Portfolio extends Model
{
    /** @use HasFactory<PortfolioFactory> */
    use HasFactory;

    protected $fillable = [
        'symbol',
        'name',
        'description',
        'country',
        'ing_id',
        'wkn',
        'share_type',
        'isin',
        'internal_isin',
        'currency',
        'active',
        'active_since',
        'sector',
        'analyst_rating_strong_sell',
        'analyst_rating_strong_buy',
        'analyst_rating_sell',
        'analyst_rating_hold'
    ];

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function profits(): HasMany
    {
        return $this->hasMany(Profit::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function stopLosses(): HasMany
    {
        return $this->hasMany(StopLoss::class);
    }

    public function savingsPlans(): HasMany
    {
        return $this->hasMany(SavingsPlan::class);
    }

    public function scopeActive(Builder $query, $active = 1): void
    {
        $query->where('active', $active);
    }

    public static function getInfoWithCompany($id): self
    {
        return self::where('id', $id)->with('company')->active()->orderBy('symbol')->first();
    }
}
