<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'symbol',
        'rate_to_base',
        'is_default',
    ];

    protected $casts = [
        'rate_to_base' => 'decimal:6',
        'is_default' => 'boolean',
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class, 'currency_code', 'code');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'currency_code', 'code');
    }
}
