<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class OrderTrackingController extends Controller
{
    public function track(Request $request, string $trackingNumber)
    {
        $order = Order::where('tracking_number', $trackingNumber)->firstOrFail();

        $items = $order->children()
            ->get(['id', 'title_snapshot', 'qty', 'price_snapshot', 'line_total']);

        return response()->json([
            'id' => $order->id,
            'tracking_number' => $order->tracking_number,
            'status' => $order->status,
            'status_note' => $order->status_note,
            'status_updated_at' => $order->status_updated_at,
            'shipping_agent' => $order->shippingAgent?->only(['id', 'name', 'email', 'phone']),
            'sales_agent' => $order->salesAgent?->only(['id', 'name', 'email', 'country_code']),
            'items' => $items,
            'customer_email' => $order->customer_email,
            'customer_name' => $order->customer_name,
            'updated_at' => $order->updated_at,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'processing', 'shipped', 'delivered', 'cancelled'])],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $order->update([
            'status' => $data['status'],
            'status_note' => $data['note'] ?? null,
            'status_updated_at' => now(),
        ]);

        $this->notifyOrderStatus($order);

        return response()->json([
            'message' => 'Order status updated.',
            'status' => $order->status,
            'tracking_number' => $order->tracking_number,
        ]);
    }

    private function notifyOrderStatus(Order $order): void
    {
        if (!$order->customer_email) {
            return;
        }

        $adminEmail = config('mail.from.address');
        $subject = "Order {$order->tracking_number} status: {$order->status}";
        $body = "Hi {$order->customer_name},\n\n"
            ."Your order status is now: {$order->status}.\n"
            .($order->status_note ? "Note: {$order->status_note}\n" : '')
            ."\nThank you.";

        Mail::raw($body, function ($message) use ($order, $adminEmail, $subject) {
            $message->to($order->customer_email, $order->customer_name ?? null)
                ->subject($subject);

            if ($adminEmail) {
                $message->cc($adminEmail);
            }
        });
    }
}
