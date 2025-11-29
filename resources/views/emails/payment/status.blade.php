@php
    $shipping = $address->shipping_address ?? [];
    $currency = $address->currency_code ?? 'GBP';
@endphp

@component('mail::message')
    {{-- Greeting --}}
    # Hi {{ $address->customer_name }},

    @if ($status === 'succeeded')
        We’ve received your payment. Here are your order details:
    @else
        We couldn’t complete your payment{{ $reason ? ' (' . $reason . ')' : '' }}. Please try again or contact support.
    @endif

    @php
        $tracking = $orderLines->first()->tracking_number ?? null;
    @endphp

    @component('mail::panel')
        **Order #:** {{ $address->order_no }}  
        **Tracking #:** {{ $tracking ?? 'TBC' }}  
        **Total:** {{ sprintf('%s %.2f', strtoupper($currency), (float) $address->grand_total) }}  
        **Subtotal:** {{ sprintf('%s %.2f', strtoupper($currency), (float) $address->subtotal) }}  
        **Shipping:** {{ sprintf('%s %.2f', strtoupper($currency), (float) $address->shipping_total) }}  
        **Discount:** {{ sprintf('-%s %.2f', strtoupper($currency), (float) $address->discount_total) }}  
        **Tax:** {{ sprintf('%s %.2f', strtoupper($currency), (float) $address->tax_total) }}  
        **Payment ref:** {{ $payment->provider_ref ?? 'N/A' }}
    @endcomponent

    ## Items
    @foreach ($orderLines as $line)
        @php
            $meta = $line->meta ?? [];
            $variant = $meta['variant'] ?? [];
            $parts = array_filter([
                $line->title_snapshot,
                isset($variant['size']) ? 'Size: ' . $variant['size'] : null,
                isset($variant['color']) ? 'Color: ' . $variant['color'] : null,
                'Qty: ' . $line->qty,
                'Line: ' . sprintf('%s %.2f', strtoupper($currency), (float) $line->line_total),
            ]);
        @endphp
        - {{ implode(' | ', $parts) }}
        @if (!empty($line->image_url))
            <br><small>Image: {{ $line->image_url }}</small>
        @endif
    @endforeach

    ## Shipping To
    {{ trim(($shipping['firstName'] ?? '') . ' ' . ($shipping['lastName'] ?? '')) }}
    {{ $shipping['address1'] ?? '' }}
    {{ $shipping['address2'] ?? '' }}
    {{ $shipping['city'] ?? '' }} {{ $shipping['state'] ?? '' }} {{ $shipping['postal'] ?? '' }}
    {{ $shipping['country'] ?? '' }}
    @if (!empty($shipping['phone']))
        Phone: {{ $shipping['phone'] }}
    @endif

    Thanks,
    {{ config('app.name') }}
@endcomponent
