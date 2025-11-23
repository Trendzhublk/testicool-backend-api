<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'customer_name',
        'customer_email',
        'customer_phone',
        'currency_code',
        'subtotal',
        'discount_total',
        'shipping_total',
        'tax_total',
        'grand_total',
        'status',
        'payment_status',
        'country_code',
        'shipping_address',
        'billing_address',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'shipping_total' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code', 'code');
    }
}
