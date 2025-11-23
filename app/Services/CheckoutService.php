<?php

namespace App\Services;

use App\Models\DiscountCode;
use App\Models\DiscountCodeUsage;
use App\Models\Country;
use App\Models\Product;
use App\Models\ShippingRate;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Stripe\StripeClient;

class CheckoutService
{
    private const DISCOUNT_RESERVATION_MINUTES = 30;

    private StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    /** ---------------- DISCOUNT VALIDATION ---------------- */
    public function validateDiscountCode(string $code, array $context): array
    {
        $normalizedCode = strtoupper(trim($code));
        $discount = DiscountCode::active()
            ->whereRaw('UPPER(code) = ?', [$normalizedCode])
            ->first();

        if (!$discount) {
            return ['valid' => false, 'message' => 'Invalid discount code.'];
        }

        $region = Arr::get($context, 'region');
        $currency = strtolower(Arr::get($context, 'currency', ''));
        $customerEmail = strtolower(
            Arr::get($context, 'customer_email', Arr::get($context, 'email', ''))
        );
        $now = Carbon::now();

        if ($discount->region && $region && $discount->region !== $region) {
            return ['valid' => false, 'message' => 'Code not valid for this region.'];
        }

        $min = (float)($discount->min_subtotal ?? 0);
        if ($min > 0 && (float)$context['subtotal'] < $min) {
            return ['valid' => false, 'message' => "Minimum subtotal required is {$min}."];
        }

        if ($discount->starts_at && $now->lt($discount->starts_at)) {
            return ['valid' => false, 'message' => 'Code not active yet.'];
        }

        if ($discount->expires_at && $now->gt($discount->expires_at)) {
            return ['valid' => false, 'message' => 'Code has expired.'];
        }

        if ($discount->allowed_emails) {
            $allowed = collect($discount->allowed_emails)
                ->map(fn ($email) => strtolower($email))
                ->filter()
                ->values()
                ->all();

            if (count($allowed) > 0 && (!$customerEmail || !in_array($customerEmail, $allowed, true))) {
                return ['valid' => false, 'message' => 'Code restricted to specific recipients.'];
            }
        }

        $activeUsages = $discount->usages()->active();

        if ($discount->max_redemptions && $activeUsages->count() >= $discount->max_redemptions) {
            return ['valid' => false, 'message' => 'This code has reached its redemption limit.'];
        }

        if ($discount->once_per_email) {
            if (!$customerEmail) {
                return ['valid' => false, 'message' => 'Add your email to use this code.'];
            }

            $emailUsage = (clone $activeUsages)
                ->whereRaw('LOWER(email) = ?', [$customerEmail])
                ->count();

            if ($emailUsage > 0) {
                return ['valid' => false, 'message' => 'This code can only be used once per email.'];
            }
        }

        if ($discount->max_redemptions_per_user && $customerEmail) {
            $emailUsage = (clone $activeUsages)
                ->whereRaw('LOWER(email) = ?', [$customerEmail])
                ->count();

            if ($emailUsage >= $discount->max_redemptions_per_user) {
                return ['valid' => false, 'message' => 'You have used this code the maximum number of times.'];
            }
        } elseif ($discount->max_redemptions_per_user && !$customerEmail) {
            return ['valid' => false, 'message' => 'Add your email to use this code.'];
        }

        if ($discount->type === 'amount') {
            $discountCurrency = strtolower($discount->currency ?? $currency);
            if (!$discountCurrency) {
                return ['valid' => false, 'message' => 'Currency is required for this discount.'];
            }

            if ($discount->currency && $discountCurrency !== $currency) {
                return ['valid' => false, 'message' => 'Code not valid for this currency.'];
            }
        } else {
            $discountCurrency = $currency;
        }

        $totalBefore = (float)($context['total_before_discount'] ?? ($context['subtotal'] ?? 0) + ($context['shipping_total'] ?? 0));
        $amountOff = $discount->type === 'amount' ? (float) $discount->value : 0;
        $percentOff = $discount->type === 'percent' ? (float) $discount->value : 0;

        $calculatedAmount = $this->calculateDiscountAmount([
            'type' => $discount->type,
            'amount_off' => $amountOff,
            'percent_off' => $percentOff,
        ], $totalBefore);
        $calculatedAmount = max(0, min($totalBefore, $calculatedAmount));

        return [
            'valid' => true,
            'code' => $normalizedCode,
            'type' => $discount->type,
            'amount_off' => $discount->type === 'amount' ? $amountOff : 0,
            'percent_off' => $discount->type === 'percent' ? $percentOff : 0,
            'currency' => $discountCurrency,
            'message' => 'Discount applied.',
            'discount_model' => $discount,
            'computed_amount' => $calculatedAmount,
        ];
    }

    /** ---------------- STRIPE SESSION ---------------- */
    public function createStripeCheckoutSession(array $payload): array
    {
        $currency = strtolower($payload['currency']);
        $customerEmail = strtolower(Arr::get($payload, 'customer.email', '')) ?: null;
        $region = Arr::get($payload, 'region', Arr::get($payload, 'shipping.region'));

        // 1) Re-verify cart items server-side to prevent tampering
        $verifiedItems = $this->verifyCartItems($payload['items']);

        // 2) Shipping cost from your lane rules (server source of truth)
        $shippingRate = $this->resolveShippingRate(
            Arr::get($payload, 'shipping.method'),
            $region,
            Arr::get($payload, 'shipping.country_code', Arr::get($payload, 'shipping.country')),
            $currency
        );
        $shippingCost = $shippingRate['amount'];

        // 3) Totals computed server-side
        $subtotal = collect($verifiedItems)->sum(fn($i) => $i['unit_price'] * $i['quantity']);
        $totalBeforeDiscount = $subtotal + $shippingCost;

        // 4) Discount re-check
        $discountAmount = 0;
        $discountModel = null;
        $discountPromotionCodeId = null;
        if (!empty($payload['discount']['code'])) {
            $discountResult = $this->validateDiscountCode(
                $payload['discount']['code'],
                [
                    'subtotal' => $subtotal,
                    'shipping_total' => $shippingCost,
                    'total_before_discount' => $totalBeforeDiscount,
                    'region' => Arr::get($payload, 'region'),
                    'currency' => $currency,
                    'customer_email' => $customerEmail,
                ]
            );

            if ($discountResult['valid']) {
                $discountAmount = $this->calculateDiscountAmount($discountResult, $totalBeforeDiscount);
                $discountAmount = max(0, min($totalBeforeDiscount, $discountAmount));
                $discountModel = $discountResult['discount_model'];
                $discountPromotionCodeId = $this->ensureStripePromotionCode($discountModel, $currency);
            }
        }

        $grandTotal = max(0, $totalBeforeDiscount - $discountAmount);

        // 5) Stripe line items (products)
        $lineItems = $this->buildLineItems($verifiedItems, $currency);

        // 6) Add shipping as a separate line item (simple and transparent)
        if ($shippingCost > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $shippingRate['label'] ?? 'Shipping',
                        'metadata' => [
                            'carrier' => $shippingRate['carrier'] ?? null,
                            'region' => $shippingRate['region'] ?? null,
                            'country_code' => $shippingRate['country_code'] ?? null,
                        ],
                    ],
                    'unit_amount' => (int) round($shippingCost * 100),
                ],
                'quantity' => 1,
            ];
        }

        // 7) Metadata for ops + fulfilment
        $metadata = array_merge(
            Arr::get($payload, 'metadata', []),
            [
                'region' => Arr::get($payload, 'region'),
                'shipping_method' => Arr::get($payload, 'shipping.method', $shippingRate['code'] ?? null),
                'shipping_label' => $shippingRate['label'] ?? null,
                'discount_code' => Arr::get($payload, 'discount.code'),
                'discount_type' => $discountModel?->type,
                'discount_value' => $discountModel?->value,
                'subtotal' => $subtotal,
                'shipping' => $shippingCost,
                'discount' => $discountAmount,
                'total' => $grandTotal,
            ]
        );

        // 8) Create Stripe checkout session
        $sessionPayload = [
            'mode' => 'payment',
            'line_items' => $lineItems,
            'success_url' => $payload['successUrl'],
            'cancel_url' => $payload['cancelUrl'],
            'customer_email' => $customerEmail,
            'metadata' => $metadata,
            'payment_method_types' => $this->resolvePaymentMethodTypes(Arr::get($payload, 'paymentMethod')),

            // optional for better UX:
            'shipping_address_collection' => [
                'allowed_countries' => $this->allowedCountriesForCheckout(),
            ],
        ];

        if ($discountPromotionCodeId) {
            $sessionPayload['discounts'] = [
                ['promotion_code' => $discountPromotionCodeId],
            ];
        }

        $session = $this->stripe->checkout->sessions->create($sessionPayload);

        if ($discountModel && $discountPromotionCodeId) {
            $this->reserveDiscountUsage(
                $discountModel,
                $customerEmail,
                $session->id,
                $discountAmount,
                $discountPromotionCodeId
            );
        }

        return [
            'url' => $session->url,
            'id' => $session->id,
        ];
    }

    /** ---------------- STRIPE SESSION LOOKUP ---------------- */
    public function fetchStripeCheckoutSession(string $sessionId): array
    {
        $session = $this->stripe->checkout->sessions->retrieve($sessionId, []);

        return [
            'id' => $session->id,
            'status' => $session->status ?? null,
            'payment_status' => $session->payment_status ?? null,
            'customer_email' => $session->customer_details->email ?? $session->customer_email ?? null,
            'amount_total' => isset($session->amount_total) ? $session->amount_total / 100 : null,
            'currency' => $session->currency ?? null,
            'metadata' => $session->metadata ?? [],
        ];
    }

    /** ---------------- HELPERS ---------------- */

    private function calculateDiscountAmount(array $discountData, float $totalBeforeDiscount): float
    {
        if (($discountData['type'] ?? null) === 'percent') {
            $percent = (float)($discountData['percent_off'] ?? 0);

            $amount = ($totalBeforeDiscount * $percent) / 100;
            return is_finite($amount) ? $amount : 0;
        }

        $amount = (float)($discountData['amount_off'] ?? 0);

        return is_finite($amount) ? $amount : 0;
    }

    private function buildLineItems(array $items, string $currency): array
    {
        return array_map(function ($item) use ($currency) {
            return [
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $item['name'],
                        'metadata' => [
                            'product_id' => $item['id'],
                            'size' => $item['size'] ?? null,
                            'color' => $item['color'] ?? null,
                            'variant_id' => $item['variantId'] ?? null,
                        ],
                    ],
                    'unit_amount' => (int) round($item['unit_price'] * 100),
                ],
                'quantity' => $item['quantity'],
            ];
        }, $items);
    }

    private function verifyCartItems(array $items): array
    {
        $productIds = collect($items)->pluck('id')->unique()->values();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $verified = [];
        foreach ($items as $item) {
            $product = $products->get($item['id']);
            if (!$product || !$product->is_active) {
                throw new \Exception("Invalid product in cart.");
            }

            // Use server price (ignore FE price)
            $unitPrice = (float) $product->price;
            $qty = (int) $item['quantity'];

            $verified[] = [
                'id' => $product->id,
                'name' => $product->title ?? $item['name'],
                'unit_price' => $unitPrice,
                'quantity' => $qty,
                'size' => $item['size'] ?? null,
                'color' => $item['color'] ?? null,
                'variantId' => $item['variantId'] ?? null,
            ];
        }

        return $verified;
    }

    private function resolveShippingRate(?string $method, ?string $region, ?string $countryCode, string $currency): array
    {
        $countryCode = $countryCode ? trim($countryCode) : null;
        if ($countryCode) {
            if (strlen($countryCode) > 2) {
                $match = Country::query()
                    ->whereRaw('LOWER(name) = ?', [strtolower($countryCode)])
                    ->orWhereRaw('LOWER(code) = ?', [strtolower($countryCode)])
                    ->first();
                $countryCode = $match?->code ?? strtoupper(substr($countryCode, 0, 2));
            } else {
                $countryCode = strtoupper($countryCode);
            }
        }

        $currencyUpper = strtoupper($currency);

        $query = ShippingRate::query()
            ->where('is_active', true)
            ->when($method, fn($q) => $q->where('code', $method))
            ->when($countryCode, function ($q) use ($countryCode) {
                $q->where(function ($inner) use ($countryCode) {
                    $inner->whereNull('country_code')
                        ->orWhere('country_code', $countryCode);
                });
            })
            ->orderBy('priority')
            ->orderByRaw('country_code IS NULL');

        $rate = $query->first();

        if (!$rate && $region) {
            $rate = ShippingRate::query()
                ->where('is_active', true)
                ->when($method, fn($q) => $q->where('code', $method))
                ->whereNull('region')
                ->orderBy('priority')
                ->first();
        }

        if (!$rate) {
            throw new \RuntimeException('Shipping method not available for this destination.');
        }

        $selectedAmount = (float) $rate->amount;
        $selectedTax = (float) $rate->tax_percent;
        $selectedCurrency = $rate->currency ? strtoupper($rate->currency) : $currencyUpper;

        if (is_array($rate->currency_rates) && count($rate->currency_rates) > 0) {
            $matched = collect($rate->currency_rates)
                ->first(fn($item) => strtoupper($item['currency'] ?? '') === $currencyUpper);

            if ($matched) {
                $selectedAmount = (float) ($matched['amount'] ?? $selectedAmount);
                $selectedTax = (float) ($matched['tax_percent'] ?? $selectedTax);
                $selectedCurrency = strtoupper($matched['currency'] ?? $selectedCurrency);
            }
        }

        // Apply tax on flat amounts; percentage charge_type remains as-is for caller to handle.
        $finalAmount = $selectedAmount;
        if ($rate->charge_type === 'flat' && $selectedTax > 0) {
            $finalAmount += ($selectedAmount * $selectedTax) / 100;
        }

        return [
            'code' => $rate->code,
            'label' => $rate->label,
            'carrier' => $rate->carrier,
            'country_code' => $rate->country_code,
            'amount' => $finalAmount,
            'base_amount' => $selectedAmount,
            'currency' => $selectedCurrency,
            'priority' => $rate->priority,
            'rate_basis' => $rate->rate_basis,
            'charge_type' => $rate->charge_type,
            'tax_percent' => $selectedTax,
            'currency_rates' => $rate->currency_rates,
        ];
    }

    private function ensureStripePromotionCode(DiscountCode $discount, string $currency): ?string
    {
        if ($discount->stripe_promotion_code_id) {
            return $discount->stripe_promotion_code_id;
        }

        $couponCurrency = strtolower($discount->currency ?? $currency);
        $couponPayload = [
            'duration' => 'once',
        ];

        $existing = $this->stripe->promotionCodes->all([
            'code' => $discount->code,
            'active' => true,
            'limit' => 1,
        ]);

        if (!empty($existing->data[0])) {
            $existingPromo = $existing->data[0];
            $discount->update([
                'stripe_coupon_id' => $existingPromo->coupon->id ?? $discount->stripe_coupon_id,
                'stripe_promotion_code_id' => $existingPromo->id,
                'currency' => $discount->currency ?? $couponCurrency,
            ]);

            return $existingPromo->id;
        }

        if ($discount->type === 'percent') {
            $couponPayload['percent_off'] = (float) $discount->value;
        } else {
            $couponPayload['amount_off'] = (int) round(((float) $discount->value) * 100);
            $couponPayload['currency'] = $couponCurrency;
        }

        if ($discount->expires_at) {
            $couponPayload['redeem_by'] = $discount->expires_at->timestamp;
        }

        if ($discount->max_redemptions) {
            $couponPayload['max_redemptions'] = $discount->max_redemptions;
        }

        $coupon = $this->stripe->coupons->create($couponPayload);

        $promotion = $this->stripe->promotionCodes->create([
            'coupon' => $coupon->id,
            'code' => $discount->code,
            'max_redemptions' => $discount->max_redemptions,
            'expires_at' => $discount->expires_at?->timestamp,
        ]);

        $discount->update([
            'stripe_coupon_id' => $coupon->id,
            'stripe_promotion_code_id' => $promotion->id,
            'currency' => $discount->currency ?? $couponCurrency,
        ]);

        return $promotion->id;
    }

    private function reserveDiscountUsage(
        DiscountCode $discount,
        ?string $email,
        string $sessionId,
        ?float $amount,
        ?string $promotionCodeId = null
    ): void {
        DiscountCodeUsage::updateOrCreate(
            [
                'discount_code_id' => $discount->id,
                'stripe_checkout_session_id' => $sessionId,
            ],
            [
                'email' => $email,
                'status' => 'reserved',
                'reserved_until' => now()->addMinutes(self::DISCOUNT_RESERVATION_MINUTES),
                'metadata' => [
                    'amount' => $amount,
                    'promotion_code_id' => $promotionCodeId,
                ],
            ]
        );
    }

    private function resolvePaymentMethodTypes(?string $selected): array
    {
        $configured = config('services.stripe.payment_method_types', ['card']);
        $fallback = ['card'];
        $map = [
            'card' => ['card'],
            'apple_pay' => ['card'],
            'google_pay' => ['card'],
            'paypal' => ['paypal', 'card'],
        ];

        $candidate = $map[$selected] ?? $configured;
        $types = array_values(array_intersect($candidate, $configured));

        return count($types) > 0 ? $types : $fallback;
    }

    private function allowedCountriesForCheckout(): array
    {
        $fromRates = ShippingRate::query()
            ->where('is_active', true)
            ->whereNotNull('country_code')
            ->pluck('country_code')
            ->filter()
            ->map(fn ($c) => strtoupper($c))
            ->unique()
            ->values()
            ->all();

        return count($fromRates) > 0
            ? $fromRates
            : ['GB', 'IE', 'FR', 'DE', 'NL', 'BE', 'US', 'AE', 'AU', 'NZ'];
    }
}
