<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StopLoss extends Model
{
    protected $fillable = [
        'value',
        'portfolio_id',
    ];
}
