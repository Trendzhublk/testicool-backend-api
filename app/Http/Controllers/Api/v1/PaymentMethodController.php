<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $configured = config('services.stripe.payment_method_types', ['card']);
        $configured = array_map('strtolower', $configured);

        $methods = PaymentMethod::query()
            ->where('is_active', true)
            ->get()
            ->map(function ($method) use ($configured) {
                $stripeType = strtolower($method->stripe_type ?? $method->code);
                $isEnabledInStripe = in_array($stripeType, $configured, true);

                return [
                    'id' => $method->id,
                    'name' => $method->name,
                    'code' => $method->code,
                    'provider' => $method->provider,
                    'stripe_type' => $method->stripe_type,
                    'fee_type' => $method->fee_type,
                    'fee_amount' => (float) $method->fee_amount,
                    'badge' => $method->badge,
                    'description' => $method->description,
                    'metadata' => $method->metadata,
                    'enabled_in_stripe' => $isEnabledInStripe,
                ];
            })
            ->values();

        return response()->json($methods);
    }
}
