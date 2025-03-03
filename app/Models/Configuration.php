<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    protected $fillable = [
        'key',
        'value',
        'name',
        ];

    public static function getHistory(): bool
    {
        $configuration = self::where('key', 'get_history')->first();
        return $configuration && $configuration->value;
    }
}
