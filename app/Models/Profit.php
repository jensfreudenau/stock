<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    protected $fillable = [
        'symbol',
        'sell_id',
        'profit',
        'transaction_at',
    ];
}
