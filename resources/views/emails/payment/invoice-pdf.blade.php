<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $address->order_no }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111; margin: 0; padding: 24px; }
        h1 { margin-bottom: 4px; }
        .meta, .items, .totals { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .meta td { padding: 4px 0; }
        .items th, .items td { padding: 8px; border: 1px solid #ddd; font-size: 12px; }
        .items th { background: #f5f5f5; text-align: left; }
        .totals td { padding: 6px 4px; font-size: 12px; }
        .text-right { text-align: right; }
        .muted { color: #555; font-size: 12px; }
        .thumb { width: 70px; height: 70px; object-fit: cover; border-radius: 4px; }
    </style>
</head>
<body>
    @php
        $shipping = $address->shipping_address ?? [];
        $currency = $address->currency_code ?? 'GBP';
    @endphp

    <h1>Invoice</h1>
    <div class="muted">Order #{{ $address->order_no }}</div>

    <table class="meta">
        <tr>
            <td><strong>Customer:</strong> {{ $address->customer_name }}</td>
            <td><strong>Email:</strong> {{ $address->customer_email }}</td>
        </tr>
        <tr>
            <td><strong>Payment Status:</strong> {{ ucfirst($payment->status ?? 'paid') }}</td>
            <td><strong>Payment Ref:</strong> {{ $payment->provider_ref ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td colspan="2"><strong>Ship To:</strong>
                {{ trim(($shipping['firstName'] ?? '') . ' ' . ($shipping['lastName'] ?? '')) }},
                {{ $shipping['address1'] ?? '' }}
                {{ $shipping['address2'] ?? '' }}
                {{ $shipping['city'] ?? '' }} {{ $shipping['state'] ?? '' }} {{ $shipping['postal'] ?? '' }}
                {{ $shipping['country'] ?? '' }}
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Item</th>
                <th>Details</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Line Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderLines as $line)
                @php
                    $meta = $line->meta ?? [];
                    $variant = $meta['variant'] ?? [];
                    $image = $line->image_url ?? ($line->product->cover_image_url ?? null);
                    $details = array_filter([
                        isset($variant['size']) ? 'Size: ' . $variant['size'] : null,
                        isset($variant['color']) ? 'Color: ' . $variant['color'] : null,
                        'SKU: ' . ($line->sku_snapshot ?? 'N/A'),
                    ]);
                @endphp
                <tr>
                    <td>
                        <div><strong>{{ $line->title_snapshot }}</strong></div>
                        @if ($image)
                            <img class="thumb" src="{{ $image }}" alt="Product image">
                        @endif
                    </td>
                    <td>{{ implode(' | ', $details) }}</td>
                    <td class="text-right">{{ $line->qty }}</td>
                    <td class="text-right">{{ sprintf('%s %.2f', strtoupper($currency), (float) $line->line_total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td class="text-right"><strong>Subtotal:</strong></td>
            <td class="text-right">{{ sprintf('%s %.2f', strtoupper($currency), (float) $address->subtotal) }}</td>
        </tr>
        <tr>
            <td class="text-right"><strong>Shipping:</strong></td>
            <td class="text-right">{{ sprintf('%s %.2f', strtoupper($currency), (float) $address->shipping_total) }}</td>
        </tr>
        <tr>
            <td class="text-right"><strong>Discount:</strong></td>
            <td class="text-right">{{ sprintf('-%s %.2f', strtoupper($currency), (float) $address->discount_total) }}</td>
        </tr>
        <tr>
            <td class="text-right"><strong>Tax:</strong></td>
            <td class="text-right">{{ sprintf('%s %.2f', strtoupper($currency), (float) $address->tax_total) }}</td>
        </tr>
        <tr>
            <td class="text-right"><strong>Total:</strong></td>
            <td class="text-right"><strong>{{ sprintf('%s %.2f', strtoupper($currency), (float) $address->grand_total) }}</strong></td>
        </tr>
    </table>

    <p class="muted">If you have any questions, contact us at support@testicool.co.uk.</p>
</body>
</html>
