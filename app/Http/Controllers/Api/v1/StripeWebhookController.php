<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\DiscountCodeUsage;
use Illuminate\Http\Request;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $secret = config('services.stripe.webhook_secret');
        $signature = $request->header('Stripe-Signature');

        if (!$secret || !$signature) {
            return response()->json(['message' => 'Webhook not configured'], 400);
        }

        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                $signature,
                $secret
            );
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Invalid webhook signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $this->handleCheckoutSessionCompleted($event->data->object);
        }

        return response()->json(['received' => true]);
    }

    private function handleCheckoutSessionCompleted($session): void
    {
        if (!$session?->id) {
            return;
        }

        $usage = DiscountCodeUsage::where('stripe_checkout_session_id', $session->id)->first();
        if (!$usage) {
            return;
        }

        if ($usage->status !== 'redeemed') {
            $usage->update([
                'status' => 'redeemed',
                'redeemed_at' => now(),
                'stripe_customer_id' => $session->customer ?? null,
                'metadata' => array_merge($usage->metadata ?? [], [
                    'payment_intent' => $session->payment_intent ?? null,
                    'payment_status' => $session->payment_status ?? null,
                ]),
            ]);

            if ($usage->discountCode) {
                $usage->discountCode->increment('usage_count');
            }
        }
    }
}
