<?php

namespace App\Services;

use App\Mail\DeliveryFeedbackMail;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

class OrderStatusNotifier
{
    public function send(Order $order): void
    {
        if (!$order->customer_email) {
            return;
        }

        $items = $this->mapItems($order);

        $adminEmail = config('mail.from.address');
        $mail = Mail::to($order->customer_email, $order->customer_name ?? null);

        if ($adminEmail) {
            $mail->cc($adminEmail);
        }

        $mail->send(new OrderStatusMail($order, $items));

        if ($order->status === 'delivered') {
            $this->sendFeedback($order);
        }
    }

    private function sendFeedback(Order $order): void
    {
        $feedbackUrl = config('services.feedback.url', config('app.url') . '/feedback');
        $link = $this->appendOrderId($feedbackUrl, $order->order_id ?? $order->id);

        Mail::to($order->customer_email, $order->customer_name ?? null)
            ->send(new DeliveryFeedbackMail($order, $link));
    }

    private function mapItems(Order $order)
    {
        return $order->children()
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
                $imageUrl = $this->assetUrl(
                    $item->variant?->images?->sortBy('sort_order')->first()?->path
                        ?? $item->product?->cover_image_url
                );

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
                ];
            });
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

    private function appendOrderId(string $url, int $orderId): string
    {
        $separator = str_contains($url, '?') ? '&' : '?';
        return $url . $separator . 'orderId=' . $orderId;
    }
}
