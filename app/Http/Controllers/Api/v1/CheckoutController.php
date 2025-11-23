<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ShippingAgent;
use App\Models\ShippingRate;
use App\Services\CheckoutService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function __construct(private CheckoutService $checkoutService) {}

    public function validateDiscount(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50'],
            'currency' => ['required', 'string', 'size:3'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'shipping_total' => ['required', 'numeric', 'min:0'],
            'total_before_discount' => ['required', 'numeric', 'min:0'],
            'region' => ['nullable', 'string', 'max:20'],
            'customer_email' => ['nullable', 'email', 'max:150'],
        ]);

        $result = $this->checkoutService->validateDiscountCode(
            strtoupper(trim($validated['code'])),
            $validated
        );

        if (!$result['valid']) {
            return response()->json([
                'valid' => false,
                'message' => $result['message'] ?? 'Code not valid.',
            ], 422);
        }

        return response()->json([
            'valid' => true,
            'code' => $result['code'],
            'type' => $result['type'], // amount|percent
            'amount_off' => $result['amount_off'] ?? 0,
            'percent_off' => $result['percent_off'] ?? 0,
            'currency' => $result['currency'] ?? $validated['currency'],
            'discount_amount' => $result['computed_amount'] ?? 0,
            'message' => $result['message'] ?? 'Discount applied.',
        ]);
    }

    public function shippingRates(Request $request)
    {
        $validated = $request->validate([
            'country' => ['nullable', 'string', 'max:100'],
        ]);

        $country = $validated['country'] ?? null;
        if ($country) {
            if (strlen($country) > 2) {
                $match = Country::query()
                    ->whereRaw('LOWER(name) = ?', [strtolower($country)])
                    ->orWhereRaw('LOWER(code) = ?', [strtolower($country)])
                    ->first();
                $country = $match?->code ?? strtoupper(substr($country, 0, 2));
            } else {
                $country = strtoupper($country);
            }
        }

        $rates = ShippingRate::query()
            ->where('is_active', true)
            ->with('agent')
            ->when($country, function ($q) use ($country) {
                $q->where(function ($inner) use ($country) {
                    $inner->whereNull('country_code')
                        ->orWhere('country_code', $country);
                });
            })
            ->orderBy('priority')
            ->orderByRaw('country_code IS NULL')
            ->get([
                'id',
                'code',
                'label',
                'carrier',
                'shipping_agent_id',
                'country_code',
                'amount',
                'currency',
                'weight_min',
                'weight_max',
                'rate_basis',
                'charge_type',
                'tax_percent',
                'qty_min',
                'qty_max',
                'estimated_days',
                'priority',
                'notes',
                'currency_rates',
            ]);

        $withAgents = $rates->map(function ($rate) {
            $payload = $rate->makeHidden(['amount', 'currency'])->toArray();

            return array_merge($payload, [
                'shipping_agent' => $rate->agent?->only(['id', 'name', 'email', 'phone']),
            ]);
        });

        return response()->json($withAgents);
    }

    public function shippingAgents(Request $request)
    {
        $validated = $request->validate([
            'region' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
        ]);

        $country = $validated['country'] ?? null;
        if ($country) {
            if (strlen($country) > 2) {
                $match = Country::query()
                    ->whereRaw('LOWER(name) = ?', [strtolower($country)])
                    ->orWhereRaw('LOWER(code) = ?', [strtolower($country)])
                    ->first();
                $country = $match?->code ?? strtoupper(substr($country, 0, 2));
            } else {
                $country = strtoupper($country);
            }
        }

        $agents = ShippingAgent::query()
            ->where('is_active', true)
            ->when($validated['region'] ?? null, fn($q, $region) => $q->where('region', $region))
            ->when($country, function ($q) use ($country) {
                $q->where(function ($inner) use ($country) {
                    $inner->whereNull('country_code')->orWhere('country_code', $country);
                });
            })
            ->orderBy('priority')
            ->orderByRaw('country_code IS NULL')
            ->get([
                'id',
                'name',
                'email',
                'phone',
                'region',
                'country_code',
                'priority',
                'notes',
                'metadata',
            ]);

        return response()->json($agents);
    }

    public function createStripeSession(Request $request)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer'],
            'items.*.name' => ['required', 'string'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.size' => ['nullable', 'string', 'max:50'],
            'items.*.color' => ['nullable', 'string', 'max:50'],
            'items.*.variantId' => ['nullable'],

            'currency' => ['required', 'string', 'size:3'],
            'successUrl' => ['required', 'url'],
            'cancelUrl' => ['required', 'url'],

            'customer' => ['nullable', 'array'],
            'customer.firstName' => ['nullable', 'string', 'max:100'],
            'customer.lastName' => ['nullable', 'string', 'max:100'],
            'customer.email' => ['nullable', 'email', 'max:150'],
            'customer.phone' => ['nullable', 'string', 'max:30'],

            'shipping' => ['nullable', 'array'],
            'shipping.firstName' => ['nullable', 'string', 'max:100'],
            'shipping.lastName' => ['nullable', 'string', 'max:100'],
            'shipping.email' => ['nullable', 'email', 'max:150'],
            'shipping.phone' => ['nullable', 'string', 'max:30'],
            'shipping.address1' => ['nullable', 'string', 'max:255'],
            'shipping.address2' => ['nullable', 'string', 'max:255'],
            'shipping.city' => ['nullable', 'string', 'max:100'],
            'shipping.state' => ['nullable', 'string', 'max:100'],
            'shipping.postal' => ['nullable', 'string', 'max:30'],
            'shipping.country' => ['nullable', 'string', 'max:100'],
            'shipping.region' => ['nullable', 'string', 'max:20'],
            'shipping.country_code' => ['nullable', 'string', 'max:5'],
            'shipping.agentNote' => ['nullable', 'string', 'max:500'],
            'shipping.method' => ['nullable', 'string', 'max:100'],

            'paymentMethod' => ['nullable', 'string', 'max:30'],
            'discount' => ['nullable', 'array'],
            'discount.code' => ['nullable', 'string', 'max:50'],

            'region' => ['nullable', 'string', 'max:20'],
            'metadata' => ['nullable', 'array'],
            'totals' => ['nullable', 'array'],
        ]);

        try {
            $session = $this->checkoutService->createStripeCheckoutSession($validated);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage() ?: 'Could not start checkout.',
            ], 422);
        }

        return response()->json([
            'url' => $session['url'] ?? null,
            'session_id' => $session['id'] ?? null,
        ]);
    }

    public function getStripeSession(Request $request)
    {
        $validated = $request->validate([
            'session_id' => ['required', 'string'],
        ]);

        try {
            $session = $this->checkoutService->fetchStripeCheckoutSession($validated['session_id']);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage() ?: 'Could not load session.',
            ], 422);
        }

        return response()->json($session);
    }
}
