<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\CheckoutService;

class DiscountCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'description',
        'type',
        'value',
        'currency',
        'min_subtotal',
        'region',
        'starts_at',
        'expires_at',
        'max_redemptions',
        'max_redemptions_per_user',
        'once_per_email',
        'allowed_emails',
        'usage_count',
        'stripe_coupon_id',
        'stripe_promotion_code_id',
        'is_active'
    ];

    protected $casts = [
        'allowed_emails' => 'array',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'once_per_email' => 'boolean',
        'value' => 'decimal:2',
        'min_subtotal' => 'decimal:2',
        'max_redemptions' => 'integer',
        'max_redemptions_per_user' => 'integer',
        'usage_count' => 'integer',
    ];

    public function usages(): HasMany
    {
        return $this->hasMany(DiscountCodeUsage::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        $now = now();

        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
            });
    }

    protected static function booted(): void
    {
        static::saved(function (self $discount) {
            // Avoid extra API calls when already synced
            if ($discount->stripe_promotion_code_id) {
                return;
            }

            try {
                app(CheckoutService::class)->syncDiscountToStripe($discount);
            } catch (\Throwable $e) {
                logger()->error('Stripe promo sync failed', [
                    'discount_id' => $discount->id,
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }
}
