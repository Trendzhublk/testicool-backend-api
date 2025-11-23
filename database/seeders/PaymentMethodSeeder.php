<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $methods = [
            [
                'name' => 'Card (Stripe)',
                'code' => 'card',
                'provider' => 'stripe',
                'stripe_type' => 'card',
                'fee_type' => 'percent',
                'fee_amount' => 0,
                'badge' => 'Preferred',
                'description' => 'Visa, Mastercard, Amex',
                'is_active' => true,
            ],
            [
                'name' => 'PayPal',
                'code' => 'paypal',
                'provider' => 'stripe',
                'stripe_type' => 'paypal',
                'fee_type' => 'percent',
                'fee_amount' => 0,
                'badge' => 'Wallet',
                'description' => 'Pay with PayPal account',
                'is_active' => true,
            ],
            [
                'name' => 'Apple Pay',
                'code' => 'apple_pay',
                'provider' => 'stripe',
                'stripe_type' => 'card',
                'fee_type' => 'percent',
                'fee_amount' => 0,
                'badge' => 'Wallet',
                'description' => 'Apple Pay on supported devices',
                'is_active' => true,
            ],
            [
                'name' => 'Google Pay',
                'code' => 'google_pay',
                'provider' => 'stripe',
                'stripe_type' => 'card',
                'fee_type' => 'percent',
                'fee_amount' => 0,
                'badge' => 'Wallet',
                'description' => 'Google Pay on supported devices',
                'is_active' => true,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(['code' => $method['code']], $method);
        }
    }
}
