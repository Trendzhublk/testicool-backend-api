<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscountCodeUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'discount_code_id',
        'email',
        'status',
        'reserved_until',
        'redeemed_at',
        'stripe_checkout_session_id',
        'stripe_customer_id',
        'metadata',
    ];

    protected $casts = [
        'reserved_until' => 'datetime',
        'redeemed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function discountCode(): BelongsTo
    {
        return $this->belongsTo(DiscountCode::class);
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'redeemed')
                ->orWhere(function ($q2) {
                    $q2->where('status', 'reserved')
                        ->where(function ($q3) {
                            $q3->whereNull('reserved_until')
                                ->orWhere('reserved_until', '>', now());
                        });
                });
        });
    }
}
