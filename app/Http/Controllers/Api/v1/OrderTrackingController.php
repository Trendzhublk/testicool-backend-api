<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Mail\OrderStatusMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class OrderTrackingController extends Controller
{
    public function track(Request $request, string $trackingNumber)
    {
        $order = Order::where('tracking_number', $trackingNumber)->firstOrFail();

        $items = $order->children()
            ->with(['product.images', 'variant.images'])
            ->get([
                'id',
                'title_snapshot',
                'qty',
                'price_snapshot',
                'line_total',
                'sku_snapshot',
                'size_snapshot',
                'color_snapshot',
                'color_hex_snapshot',
                'variant_id',
                'meta',
            ])
            ->map(function ($item) {
                $variantMeta = $item->meta['variant'] ?? [];
                $clientMeta = $item->meta['client'] ?? [];
                $variantImage = $item->variant?->images?->sortBy('sort_order')->first()?->path;
                $imageUrl = $variantImage
                    ? $this->assetUrl($variantImage)
                    : ($item->product?->cover_image_url ?? null);

                return [
                    'id' => $item->id,
                    'title' => $item->title_snapshot,
                    'qty' => $item->qty,
                    'price' => $item->price_snapshot,
                    'line_total' => $item->line_total,
                    'sku' => $item->sku_snapshot,
                    'variant_id' => $item->variant_id,
                    'size' => $item->size_snapshot ?? ($variantMeta['size'] ?? null),
                    'size_id' => $variantMeta['size_id'] ?? null,
                    'color' => $item->color_snapshot ?? ($variantMeta['color'] ?? null),
                    'color_id' => $variantMeta['color_id'] ?? null,
                    'color_hex' => $item->color_hex_snapshot ?? ($variantMeta['color_hex'] ?? null),
                    'image_url' => $imageUrl,
                    'client_meta' => $clientMeta,
                ];
            });

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

    private function assetUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        $base = rtrim(config('filesystems.disks.s3.url', config('app.asset_url', '')), '/');
        return $base ? $base . '/' . ltrim($path, '/') : $path;
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

        $items = $order->children()
            ->get([
                'id',
                'title_snapshot',
                'qty',
                'price_snapshot',
                'line_total',
                'sku_snapshot',
                'size_snapshot',
                'color_snapshot',
                'color_hex_snapshot',
                'variant_id',
                'meta',
            ])
            ->map(function ($item) {
                $variantMeta = $item->meta['variant'] ?? [];

                return [
                    'id' => $item->id,
                    'title' => $item->title_snapshot,
                    'qty' => $item->qty,
                    'price' => $item->price_snapshot,
                    'line_total' => $item->line_total,
                    'sku' => $item->sku_snapshot,
                    'variant_id' => $item->variant_id,
                    'size' => $item->size_snapshot ?? ($variantMeta['size'] ?? null),
                    'size_id' => $variantMeta['size_id'] ?? null,
                    'color' => $item->color_snapshot ?? ($variantMeta['color'] ?? null),
                    'color_id' => $variantMeta['color_id'] ?? null,
                    'color_hex' => $item->color_hex_snapshot ?? ($variantMeta['color_hex'] ?? null),
                ];
            });

        $adminEmail = config('mail.from.address');
        $mail = Mail::to($order->customer_email, $order->customer_name ?? null);

        if ($adminEmail) {
            $mail->cc($adminEmail);
        }

        $mail->send(new OrderStatusMail($order, $items));
    }
}
