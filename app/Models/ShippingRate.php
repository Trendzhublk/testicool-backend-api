<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'label',
        'carrier',
        'shipping_agent_id',
        'region',
        'country_code',
        'rate_basis',
        'charge_type',
        'tax_percent',
        'amount',
        'currency',
        'currency_rates',
        'weight_min',
        'weight_max',
        'qty_min',
        'qty_max',
        'estimated_days',
        'priority',
        'is_active',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_active' => 'boolean',
        'estimated_days' => 'integer',
        'priority' => 'integer',
        'metadata' => 'array',
        'tax_percent' => 'decimal:3',
        'qty_min' => 'integer',
        'qty_max' => 'integer',
        'currency_rates' => 'array',
    ];

    public function agent()
    {
        return $this->belongsTo(ShippingAgent::class, 'shipping_agent_id');
    }

    protected static function booted(): void
    {
        static::saving(function (ShippingRate $rate) {
            $rates = $rate->currency_rates ?? [];

            if (is_array($rates) && count($rates) > 0) {
                $first = $rates[0];
                $rate->currency = $first['currency'] ?? $rate->currency;
                $rate->amount = $first['amount'] ?? $rate->amount;
                $rate->tax_percent = $first['tax_percent'] ?? $rate->tax_percent;
            }
        });
    }
}
