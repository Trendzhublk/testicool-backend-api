<?php

namespace Database\Seeders;

use App\Models\DiscountCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DiscountCodeSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $now = Carbon::now();
        $expires = $now->copy()->addMonths(3);

        $codes = [
            [
                'code' => 'WELCOME10',
                'description' => '10% off welcome',
                'type' => 'percent',
                'value' => 10,
                'min_subtotal' => 0,
                'region' => null,
                'starts_at' => $now,
                'expires_at' => $expires,
                'is_active' => true,
            ],
            [
                'code' => 'SHIPFREE',
                'description' => '£15 off shipping',
                'type' => 'amount',
                'value' => 15,
                'currency' => 'GBP',
                'min_subtotal' => 50,
                'region' => 'uk',
                'starts_at' => $now,
                'expires_at' => $expires,
                'is_active' => true,
            ],
            [
                'code' => 'VIP25',
                'description' => 'VIP 25% for select emails',
                'type' => 'percent',
                'value' => 25,
                'allowed_emails' => ['vip@testicool.com', 'agent@testicool.com'],
                'min_subtotal' => 100,
                'once_per_email' => true,
                'starts_at' => $now,
                'expires_at' => $expires,
                'is_active' => true,
            ],
            [
                'code' => 'SINGLES15',
                'description' => '15% off one-time use',
                'type' => 'percent',
                'value' => 15,
                'max_redemptions' => 100,
                'max_redemptions_per_user' => 1,
                'starts_at' => $now,
                'expires_at' => $expires,
                'is_active' => true,
            ],
        ];

        foreach ($codes as $code) {
            DiscountCode::updateOrCreate(
                ['code' => $code['code']],
                array_merge($code, [
                    'stripe_coupon_id' => null,
                    'stripe_promotion_code_id' => null,
                    'usage_count' => 0,
                ])
            );
        }
    }
}
