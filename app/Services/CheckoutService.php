<?php

namespace App\Services;

use App\Models\Address;
use App\Models\DiscountCode;
use App\Models\DiscountCodeUsage;
use App\Models\Country;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingRate;
use App\Services\CurrencyConversionService;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Stripe\StripeClient;

class CheckoutService
{
    private const DISCOUNT_RESERVATION_MINUTES = 30;

    private StripeClient $stripe;

    public function __construct(private CurrencyConversionService $currencyService)
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
        $currency = $this->currencyService->normalize(Arr::get($context, 'currency'));
        $customerEmail = strtolower(
            Arr::get($context, 'customer_email', Arr::get($context, 'email', ''))
        );
        $now = Carbon::now();

        if ($discount->region && $region && $discount->region !== $region) {
            return ['valid' => false, 'message' => 'Code not valid for this region.'];
        }

        // Enforce email presence for email-scoped codes
        $requiresEmail = $discount->once_per_email
            || $discount->max_redemptions_per_user
            || (is_array($discount->allowed_emails) && count($discount->allowed_emails) > 0);
        if ($requiresEmail && !$customerEmail) {
            return ['valid' => false, 'message' => 'Add your email to use this code.'];
        }

        $min = (float) ($discount->min_subtotal ?? 0);
        if ($min > 0) {
            $minConverted = $this->currencyService->fromBase($min, $currency);

            if ((float) $context['subtotal'] < $minConverted) {
                $prettyMin = number_format($minConverted, 2);
                return ['valid' => false, 'message' => "Minimum subtotal required is {$prettyMin}."];
            }
        }

        if ($discount->starts_at && $now->lt($discount->starts_at)) {
            return ['valid' => false, 'message' => 'Code not active yet.'];
        }

        if ($discount->expires_at && $now->gt($discount->expires_at)) {
            return ['valid' => false, 'message' => 'Code has expired.'];
        }

        if ($discount->allowed_emails) {
            $allowed = collect($discount->allowed_emails)
                ->map(fn($email) => strtolower($email))
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

        $totalBefore = (float) ($context['total_before_discount']
            ?? (($context['subtotal'] ?? 0) + ($context['shipping_total'] ?? 0)));

        $amountOff = 0;
        $percentOff = 0;
        $discountCurrency = $currency;

        if ($discount->type === 'amount') {
            $discountCurrency = $this->currencyService->normalize($discount->currency ?? $currency);
            $amountOff = (float) $discount->value;

            if ($discount->currency && $discountCurrency !== $currency) {
                // Convert fixed amount discounts into the shopper's currency
                $amountOff = $this->currencyService->convert($amountOff, $discountCurrency, $currency);
                $discountCurrency = $currency;
            }
        } else {
            $percentOff = (float) $discount->value;
        }

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
        $currencyCode = $this->currencyService->normalize(
            $payload['currency'] ?? $this->currencyService->base()
        );
        $currencyRate = $this->currencyService->rate($currencyCode);
        $currencySymbol = $this->currencyService->symbol($currencyCode);
        $currency = strtolower($currencyCode);
        $customerEmail = strtolower(Arr::get($payload, 'customer.email', '')) ?: null;
        $region = Arr::get($payload, 'region', Arr::get($payload, 'shipping.region'));

        // 1) Re-verify cart items server-side to prevent tampering
        $verifiedItems = $this->verifyCartItems($payload['items']);
        $pricedItems = collect($verifiedItems)->map(function ($item) use ($currencyRate, $currencyCode) {
            $unitBase = $item['unit_price_base'] ?? $item['unit_price'];
            $converted = round($unitBase * $currencyRate, 2);

            return array_merge($item, [
                'unit_price' => $converted,
                'unit_price_base' => $unitBase,
                'currency_code' => $currencyCode,
            ]);
        })->all();

        // 2) Shipping cost from your lane rules (server source of truth)
        $shippingRate = $this->resolveShippingRate(
            Arr::get($payload, 'shipping.method'),
            $region,
            Arr::get($payload, 'shipping.country_code', Arr::get($payload, 'shipping.country')),
            $currencyCode
        );
        $shippingCost = $shippingRate['amount'];
        $shippingTax = $shippingRate['tax_amount'] ?? 0;

        // 3) Totals computed server-side
        $subtotal = collect($pricedItems)->sum(fn($i) => $i['unit_price'] * $i['quantity']);
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
                // We keep local discount metadata but will embed the discount into line items
                // to avoid passing promotion codes to Stripe Checkout.
                $discountPromotionCodeId = null;
            }
        }

        $grandTotal = max(0, $totalBeforeDiscount - $discountAmount);

        // ---------------- DB WRITE (A) Create Address header + Order lines ----------------
        $address = DB::transaction(function () use (
            $payload,
            $pricedItems,
            $subtotal,
            $shippingCost,
            $shippingRate,
            $shippingTax,
            $discountAmount,
            $grandTotal,
            $currencyCode,
            $customerEmail
        ) {
            $orderNo = 'ORD-' . strtoupper(Str::random(8));

            $shipping = Arr::get($payload, 'shipping', []);
            $billing  = Arr::get($payload, 'billing', $shipping);

            $addr = Address::create([
                'order_no' => $orderNo,
                'customer_name' => trim(
                    (Arr::get($payload, 'customer.firstName') ?? '') . ' ' .
                        (Arr::get($payload, 'customer.lastName') ?? '')
                ) ?: trim(($shipping['firstName'] ?? '') . ' ' . ($shipping['lastName'] ?? '')),
                'customer_email' => $customerEmail,
                'customer_phone' => Arr::get($payload, 'customer.phone', Arr::get($shipping, 'phone')),

                'currency_code' => $currencyCode,
                'subtotal' => $subtotal,
                'discount_total' => $discountAmount,
                'shipping_total' => $shippingCost,
                'tax_total' => $shippingTax,
                'grand_total' => $grandTotal,

                // DB-safe short status
                'status' => $this->safeStatus('pending'),
                'payment_status' => 'unpaid',

                'country_code' => Arr::get($shipping, 'country_code'),
                'shipping_address' => $shipping,
                'billing_address' => $billing,
                'notes' => Arr::get($shipping, 'agentNote'),
            ]);

            foreach ($pricedItems as $index => $item) {
                $trackingNumber = $orderNo . '-' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
                $lineTotal = $item['unit_price'] * $item['quantity'];
                $lineTotalBase = ($item['unit_price_base'] ?? 0) * $item['quantity'];

                Order::create([
                    'order_id' => $addr->id,
                    'product_id' => $item['id'],
                    'variant_id' => $item['variantId'] ?? null,
                    'sku_snapshot' => $item['variantSku'] ?? ($item['variantId'] ?? null),
                    'size_snapshot' => $item['size'] ?? null,
                    'color_snapshot' => $item['color'] ?? null,
                    'color_hex_snapshot' => $item['color_hex'] ?? null,
                    'title_snapshot' => $item['name'],
                    'price_snapshot' => $item['unit_price'],
                    'qty' => $item['quantity'],
                    'line_total' => $lineTotal,
                    'meta' => [
                        'variant' => [
                            'id' => $item['variantId'] ?? null,
                            'sku' => $item['variantSku'] ?? null,
                            'size_id' => $item['variantSizeId'] ?? null,
                            'size' => $item['size'] ?? null,
                            'color_id' => $item['variantColorId'] ?? null,
                            'color' => $item['color'] ?? null,
                            'color_hex' => $item['color_hex'] ?? null,
                        ],
                        'client' => $item['clientMeta'] ?? null,
                        'pricing' => [
                            'unit_price' => $item['unit_price'],
                            'unit_price_base' => $item['unit_price_base'] ?? null,
                            'qty' => $item['quantity'],
                            'line_total' => $lineTotal,
                            'line_total_base' => $lineTotalBase,
                            'currency' => $currencyCode,
                            'base_currency' => $this->currencyService->base(),
                            'tax' => 0,
                        ],
                        'shipping' => [
                            'method' => $shippingRate['code'] ?? null,
                            'label' => $shippingRate['label'] ?? null,
                            'cost' => $shippingCost,
                            'tax' => $shippingTax,
                            'base_cost' => $shippingRate['base_amount'] ?? null,
                            'base_currency' => $shippingRate['base_currency'] ?? $this->currencyService->base(),
                            'currency' => $currencyCode,
                        ],
                    ],
                    'tracking_number' => $trackingNumber,
                    // DB-safe short status
                    'status' => $this->safeStatus('pending'),
                    'status_note' => null,
                    'customer_email' => $customerEmail,
                    'customer_name' => $addr->customer_name,
                    'status_updated_at' => now(),
                ]);
            }

            return $addr;
        });
        // -------------------------------------------------------------------------------

        // 5) Stripe line items (products)
        $lineItems = $this->buildLineItems($pricedItems, $currency);

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

        // Embed discount directly into line item pricing to avoid Stripe promotion_code errors
        if ($discountAmount > 0) {
            $lineItems = $this->applyDiscountToLineItems($lineItems, $discountAmount);
        }

        // 7) Metadata for ops + fulfilment
        $metadata = array_merge(
            Arr::get($payload, 'metadata', []),
            [
                'address_id' => $address->id,
                'order_no' => $address->order_no,
                'region' => Arr::get($payload, 'region'),
                'shipping_method' => Arr::get($payload, 'shipping.method', $shippingRate['code'] ?? null),
                'shipping_label' => $shippingRate['label'] ?? null,
                'shipping_tax' => $shippingTax,
                'discount_code' => Arr::get($payload, 'discount.code'),
                'discount_type' => $discountModel?->type,
                'discount_value' => $discountModel?->value,
                'subtotal' => $subtotal,
                'shipping' => $shippingCost,
                'discount' => $discountAmount,
                'tax' => $shippingTax,
                'total' => $grandTotal,
                'currency' => $currencyCode,
                'currency_rate' => $currencyRate,
                'currency_symbol' => $currencySymbol,
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
            'shipping_address_collection' => [
                'allowed_countries' => $this->allowedCountriesForCheckout(),
            ],
            'payment_intent_data' => [
                'metadata' => array_merge($metadata, [
                    'session_source' => 'checkout',
                ]),
            ],
        ];

        // We embed discounts into line items; do not send discounts/promotion_code to Stripe

        $session = $this->stripe->checkout->sessions->create($sessionPayload);

        // backfill checkout_session_id onto the intent for webhook correlation
        if (!empty($session->payment_intent)) {
            try {
                $this->stripe->paymentIntents->update($session->payment_intent, [
                    'metadata' => array_merge(
                        $sessionPayload['payment_intent_data']['metadata'] ?? [],
                        [
                            'checkout_session_id' => $session->id,
                            'address_id' => $metadata['address_id'] ?? null,
                            'order_no' => $metadata['order_no'] ?? null,
                        ]
                    ),
                ]);
            } catch (\Throwable $e) {
                // non-blocking metadata sync
            }
        }

        // ---------------- DB WRITE (B) Save Stripe session id + Payment row --------------
        DB::transaction(function () use (
            $address,
            $session,
            $grandTotal,
            $currencyCode,
            $discountPromotionCodeId
        ) {
            $shipping = $address->shipping_address ?? [];
            $shipping['stripe_session_id'] = $session->id;

            $notes = trim(($address->notes ?? '') . "\nStripe session: {$session->id}");

            $address->update([
                'shipping_address' => $shipping,
                'notes' => $notes,
            ]);

            Payment::create([
                'order_id' => $address->id,
                'provider' => 'stripe',
                'provider_ref' => $session->id,
                'amount' => $grandTotal,
                'currency_code' => $currencyCode,

                // payments.status is an enum; use a value defined in the migration.
                'status' => 'initiated',

                'payload' => [
                    'session_id' => $session->id,
                    'promo_id' => $discountPromotionCodeId,
                ],
            ]);
        });
        // -------------------------------------------------------------------------------

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
            $percent = (float) ($discountData['percent_off'] ?? 0);
            $amount = ($totalBeforeDiscount * $percent) / 100;
            return is_finite($amount) ? $amount : 0;
        }

        $amount = (float) ($discountData['amount_off'] ?? 0);
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

    private function applyDiscountToLineItems(array $lineItems, float $discountAmount): array
    {
        $discountCents = (int) round($discountAmount * 100);
        if ($discountCents <= 0) {
            return $lineItems;
        }

        // Build totals in cents
        $totals = [];
        $totalCents = 0;
        foreach ($lineItems as $idx => $line) {
            $unit = (int) ($line['price_data']['unit_amount'] ?? 0);
            $qty = (int) ($line['quantity'] ?? 1);
            $lineTotal = $unit * $qty;
            $totals[$idx] = $lineTotal;
            $totalCents += $lineTotal;
        }

        if ($totalCents <= 0 || $discountCents >= $totalCents) {
            // avoid zero/negative; cap discount to leave at least 1 cent
            $discountCents = max(0, $totalCents - 1);
        }

        $remaining = $discountCents;
        $adjusted = $lineItems;

        foreach ($adjusted as $idx => &$line) {
            $unit = (int) ($line['price_data']['unit_amount'] ?? 0);
            $qty = (int) ($line['quantity'] ?? 1);
            $lineTotal = $totals[$idx];

            $isLast = $idx === array_key_last($adjusted);
            $share = $isLast ? $remaining : (int) floor(($lineTotal / $totalCents) * $discountCents);
            $share = min($share, $remaining, $lineTotal - 1); // leave at least 1 cent

            $newTotal = $lineTotal - $share;
            $remaining -= $share;

            // Recalculate unit amount with simple rounding
            $newUnit = (int) max(1, round($newTotal / max(1, $qty)));
            $line['price_data']['unit_amount'] = $newUnit;
        }
        unset($line);

        return $adjusted;
    }

    /**
     * Verify FE cart against DB:
     * - product must exist and be active
     * - unit price must come from server (variant override > product base_price)
     */
    private function verifyCartItems(array $items): array
    {
        $productIds = collect($items)->pluck('id')->unique()->values();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $variantIds = collect($items)->pluck('variantId')->filter()->unique()->values();
        $variants = $variantIds->isNotEmpty()
            ? ProductVariant::with(['color:id,name,hex', 'size:id,name'])
            ->whereIn('id', $variantIds)
            ->get()
            ->keyBy('id')
            : collect();

        $verified = [];

        foreach ($items as $item) {
            $product = $products->get($item['id']);
            if (!$product || !$product->is_active || !$product->in_stock) {
                throw new \Exception("Invalid product in cart.");
            }

            $variant = null;
            if (!empty($item['variantId'])) {
                $variant = $variants->get($item['variantId']);
                if ($variant && (!$variant->is_active || (int) $variant->product_id !== (int) $product->id)) {
                    $variant = null;
                }
            }

            $qty = (int) $item['quantity'];
            if ($qty < 1) {
                throw new \Exception('Quantity must be at least 1.');
            }

            if ($variant) {
                $available = (int) $variant->stock_qty;
                if ($available < $qty) {
                    throw new \Exception("Insufficient stock for SKU {$variant->sku}.");
                }
            }

            $unitPrice = (float) ($variant?->price_override ?? $product->base_price);

            $verified[] = [
                'id' => $product->id,
                'name' => $product->title ?? $item['name'],
                'unit_price' => $unitPrice,
                'unit_price_base' => $unitPrice,
                'quantity' => $qty,
                'size' => $variant?->size?->name ?? $item['size'] ?? null,
                'color' => $variant?->color?->name ?? $item['color'] ?? null,
                'color_hex' => $variant?->color?->hex ?? null,
                'variantColorId' => $variant?->color_id,
                'variantSizeId' => $variant?->size_id,
                'variantId' => $item['variantId'] ?? null,
                'variantSku' => $variant?->sku ?? null,
                'clientMeta' => $item['meta'] ?? null,
            ];
        }

        return $verified;
    }

    private function resolveShippingRate(?string $method, ?string $region, ?string $countryCode, string $currencyCode): array
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

        $currencyUpper = $this->currencyService->normalize($currencyCode);
        $baseCurrency = $this->currencyService->base();

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

        $amountCurrency = $rate->currency ? strtoupper($rate->currency) : $baseCurrency;
        $selectedAmount = (float) $rate->amount;
        $selectedTax = (float) $rate->tax_percent;

        $baseAmount = $this->currencyService->toBase($selectedAmount, $amountCurrency);
        $taxAmountBase = 0;

        if ($rate->charge_type === 'flat' && $selectedTax > 0) {
            $taxAmountBase = ($baseAmount * $selectedTax) / 100;
        }

        $finalBase = $baseAmount + $taxAmountBase;

        $currencyRates = collect($this->currencyService->supportedCodes())->map(function ($code) use ($finalBase) {
            return [
                'currency' => $code,
                'amount' => $this->currencyService->fromBase($finalBase, $code),
                'symbol' => $this->currencyService->symbol($code),
            ];
        })->values();

        return [
            'code' => $rate->code,
            'label' => $rate->label,
            'carrier' => $rate->carrier,
            'country_code' => $rate->country_code,
            'amount' => $this->currencyService->fromBase($finalBase, $currencyUpper),
            'base_amount' => $baseAmount,
            'base_currency' => $baseCurrency,
            'tax_amount' => $this->currencyService->fromBase($taxAmountBase, $currencyUpper),
            'currency' => $currencyUpper,
            'priority' => $rate->priority,
            'rate_basis' => $rate->rate_basis,
            'charge_type' => $rate->charge_type,
            'tax_percent' => $selectedTax,
            'currency_rates' => $currencyRates,
        ];
    }

    public function syncDiscountToStripe(DiscountCode $discount): ?string
    {
        $currency = strtolower($discount->currency ?? config('services.stripe.default_currency', 'GBP'));
        return $this->ensureStripePromotionCode($discount, $currency);
    }

    public function ensureStripePromotionCode(DiscountCode $discount, string $currency): ?string
    {
        $couponCurrency = strtolower($discount->currency ?? $currency);

        // If we already have a promo code that looks valid, use it.
        if ($discount->stripe_promotion_code_id && str_starts_with($discount->stripe_promotion_code_id, 'promo_')) {
            return $discount->stripe_promotion_code_id;
        }

        // If a coupon id was mistakenly stored in stripe_promotion_code_id, normalize it.
        if ($discount->stripe_promotion_code_id && str_starts_with($discount->stripe_promotion_code_id, 'coupon_')) {
            $discount->update(['stripe_promotion_code_id' => null, 'stripe_coupon_id' => $discount->stripe_promotion_code_id]);
        }

        // Try find an active promo with the same code
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

        // Ensure there is a coupon to attach
        $couponPayload = ['duration' => 'once'];

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

        $coupon = $discount->stripe_coupon_id
            ? $this->stripe->coupons->retrieve($discount->stripe_coupon_id)
            : $this->stripe->coupons->create($couponPayload);

        // Create promotion code tied to the coupon
        $promoPayload = [
            'coupon' => $coupon->id,
            'code' => $discount->code,
        ];

        if (!empty($discount->max_redemptions)) {
            $promoPayload['max_redemptions'] = (int) $discount->max_redemptions;
        }

        if ($discount->expires_at) {
            $promoPayload['expires_at'] = $discount->expires_at->timestamp;
        }

        $promotion = $this->stripe->promotionCodes->create($promoPayload);

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
            'google_pay' => ['card'], // Google Pay surfaces when card is allowed and domain is verified
            'paypal' => ['paypal', 'card'],
        ];

        $candidate = $map[$selected] ?? $configured;
        $types = array_values(array_unique(array_merge($candidate, $configured)));

        // Checkout only accepts known values; Google Pay rides on `card`.
        $types = array_values(array_intersect($types, ['card', 'paypal']));

        return count($types) > 0 ? $types : $fallback;
    }

    private function allowedCountriesForCheckout(): array
    {
        $fromRates = ShippingRate::query()
            ->where('is_active', true)
            ->whereNotNull('country_code')
            ->pluck('country_code')
            ->filter()
            ->map(fn($c) => strtoupper($c))
            ->unique()
            ->values()
            ->all();

        return count($fromRates) > 0
            ? $fromRates
            : ['GB', 'IE', 'FR', 'DE', 'NL', 'BE', 'US', 'AE', 'AU', 'NZ'];
    }

    private function safeStatus(string $candidate): string
    {
        $allowed = ['pending', 'paid', 'failed', 'cancelled', 'processing', 'shipped', 'completed'];
        return in_array($candidate, $allowed, true) ? $candidate : 'pending';
    }
}
