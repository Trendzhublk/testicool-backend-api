<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'provider',
        'stripe_type',
        'fee_type',
        'fee_amount',
        'is_active',
        'badge',
        'description',
        'metadata',
    ];

    protected $casts = [
        'fee_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];
}
