<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\DiscountCodeUsage;
use Illuminate\Http\Request;
<<<<<<< HEAD
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
=======
>>>>>>> parent of 829bb1a (updated)
use Stripe\Webhook;
use App\Mail\PaymentStatusMail;
use App\Mail\PaymentInvoiceMail;

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

<<<<<<< HEAD
        // Find Address by embedded session id in shipping_address OR notes fallback
        $address = Address::whereJsonContains('shipping_address->stripe_session_id', $session->id)
            ->orWhere('notes', 'like', '%' . $session->id . '%')
            ->first();

        if ($address && $address->payment_status !== 'paid') {
            DB::transaction(function () use ($address, $session) {

                // 1) Mark Address paid
                $address->update([
                    'status' => 'paid',
                    'payment_status' => 'paid',
                    'notes' => trim(($address->notes ?? '') . "\nPaid via Stripe."),
                ]);

                // 2) Mark all Order lines paid
                $orderLines = Order::where('order_id', $address->id)
                    ->with('product.images')
                    ->get()
                    ->each(function ($line) {
                        $line->image_url = $line->product?->cover_image_url;
                    });

                Order::where('order_id', $address->id)
                    ->update([
                        // orders.status enum supports: pending, processing, shipped, delivered, cancelled
                        'status' => 'processing',
                        'status_updated_at' => now(),
                        'status_note' => 'Stripe checkout.session.completed',
                    ]);

                // 2b) Reserve/deduct variant stock (one adjustment per variant)
                $variantQty = $orderLines
                    ->whereNotNull('variant_id')
                    ->groupBy('variant_id')
                    ->map(fn($rows) => (int) $rows->sum('qty'));

                $adjustments = [];

                if ($variantQty->isNotEmpty()) {
                    $variants = ProductVariant::whereIn('id', $variantQty->keys())
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');

                    foreach ($variantQty as $variantId => $qty) {
                        $variant = $variants->get($variantId);
                        if (!$variant) {
                            continue;
                        }

                        $newStock = max(0, (int) $variant->stock_qty - $qty);
                        if ($newStock !== (int) $variant->stock_qty) {
                            $variant->stock_qty = $newStock;
                            $variant->save();
                        }

                        $adjustments[] = [
                            'variant_id' => $variant->id,
                            'sku' => $variant->sku,
                            'deducted' => $qty,
                            'stock_after' => $newStock,
                        ];
                    }
                }

                // 3) Mark Payment paid (use model update to respect casts)
                $payment = Payment::where('provider', 'stripe')
                    ->lockForUpdate()
                    ->where('provider_ref', $session->id)
                    ->first();

                if ($payment) {
                    $payload = array_merge(
                        $payment->payload ?? [],
                        [
                            'payment_intent' => $session->payment_intent ?? null,
                            'payment_status' => $session->payment_status ?? null,
                            'customer' => $session->customer ?? null,
                        ]
                    );

                    if (!empty($adjustments)) {
                        $payload['inventory_adjustments'] = $adjustments;
                    }

                    $payment->update([
                        // payments.status enum supports: initiated, succeeded, failed, refunded
                        'status' => 'succeeded',
                        'payload' => $payload,
                    ]);

                    $this->sendPaymentEmail($address, $orderLines, $payment, 'succeeded');
                }
            });
        }

        // ---- existing discount usage logic stays intact ----
=======
>>>>>>> parent of 829bb1a (updated)
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
<<<<<<< HEAD

    private function handleCheckoutSessionFailed($session, string $reason): void
    {
        if (!$session?->id) {
            return;
        }

        $address = Address::whereJsonContains('shipping_address->stripe_session_id', $session->id)
            ->orWhere('notes', 'like', '%' . $session->id . '%')
            ->first();

        if (!$address) {
            return;
        }

        DB::transaction(function () use ($address, $session, $reason) {
            $orderLines = Order::where('order_id', $address->id)
                ->with('product.images')
                ->get()
                ->each(function ($line) {
                    $line->image_url = $line->product?->cover_image_url;
                });

            Order::where('order_id', $address->id)->update([
                'status_note' => 'Stripe ' . $reason,
                'status_updated_at' => now(),
            ]);

            $address->update([
                'payment_status' => 'unpaid',
                'notes' => trim(($address->notes ?? '') . "\nPayment issue: {$reason}"),
            ]);

            $payment = Payment::where('provider', 'stripe')
                ->lockForUpdate()
                ->where('provider_ref', $session->id)
                ->first();

            $alreadyFailed = $payment && $payment->status === 'failed';

            if ($payment) {
                $payload = array_merge(
                    $payment->payload ?? [],
                    [
                        'payment_status' => $session->payment_status ?? 'failed',
                        'failure_reason' => $reason,
                        'last_payment_error' => $session->last_payment_error->message ?? null,
                    ]
                );

                $payment->update([
                    'status' => 'failed',
                    'payload' => $payload,
                ]);
            }

            if (!$alreadyFailed) {
                $this->sendPaymentEmail($address, $orderLines, $payment, 'failed', $reason);
            }
        });
    }

    private function handlePaymentIntentFailed($intent): void
    {
        if (!$intent?->id) {
            return;
        }

        $sessionId = $intent->metadata->checkout_session_id ?? $intent->metadata->session_id ?? null;
        $addressId = $intent->metadata->address_id ?? null;
        $failureMessage = $intent->last_payment_error->message ?? 'Payment failed';

        $payment = Payment::where('provider', 'stripe')
            ->where(function ($q) use ($sessionId, $addressId, $intent) {
                if ($sessionId) {
                    $q->orWhere('provider_ref', $sessionId);
                }
                if ($addressId) {
                    $q->orWhere('order_id', $addressId);
                }
                $q->orWhere('payload->payment_intent', $intent->id);
            })
            ->first();

        if (!$payment && $addressId) {
            $payment = Payment::where('provider', 'stripe')
                ->where('order_id', $addressId)
                ->latest()
                ->first();
        }

        $address = $addressId ? Address::find($addressId) : null;

        if (!$address && $payment) {
            $address = Address::find($payment->order_id);
        }

        if (!$address) {
            return;
        }

        DB::transaction(function () use ($address, $payment, $failureMessage, $intent) {
            $orderLines = Order::where('order_id', $address->id)
                ->with('product.images')
                ->get()
                ->each(function ($line) {
                    $line->image_url = $line->product?->cover_image_url;
                });

            Order::where('order_id', $address->id)->update([
                'status_note' => $failureMessage,
                'status_updated_at' => now(),
            ]);

            $address->update([
                'payment_status' => 'unpaid',
                'notes' => trim(($address->notes ?? '') . "\nPayment issue: {$failureMessage}"),
            ]);

            $alreadyFailed = $payment && $payment->status === 'failed';

            if ($payment) {
                $payload = array_merge(
                    $payment->payload ?? [],
                    [
                        'payment_intent' => $intent->id,
                        'failure_reason' => $failureMessage,
                    ]
                );

                $payment->update([
                    'status' => 'failed',
                    'payload' => $payload,
                ]);
            }

            if (!$alreadyFailed) {
                $this->sendPaymentEmail($address, $orderLines, $payment, 'failed', $failureMessage);
            }
        });
    }

    private function sendPaymentEmail(Address $address, $orderLines, ?Payment $payment, string $status, ?string $reason = null): void
    {
        if (!$address->customer_email) {
            return;
        }

        $adminEmail = config('mail.from.address');
        $lines = collect($orderLines);
        $supportEmail = 'support@testicool.co.uk';

        if ($status === 'succeeded') {
            $mailable = new PaymentInvoiceMail($address, $lines, $payment);
        } else {
            $mailable = new PaymentStatusMail(
                $address,
                $lines,
                $payment,
                $status,
                $reason
            );
        }

        if ($adminEmail) {
            $mailable->cc($adminEmail);
        }

        $mailable->replyTo($supportEmail, 'Testicool Support');

        $mail = Mail::to($address->customer_email, $address->customer_name ?? null);

        $mail->send($mailable);
    }
=======
>>>>>>> parent of 829bb1a (updated)
}
