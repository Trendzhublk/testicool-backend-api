@php
    $currency = $order->parent?->address?->currency_code ?? $order->currency_code ?? 'GBP';
@endphp

@component('mail::message')
# Order Status Update

Hi {{ $order->customer_name ?? 'Customer' }},

Your order status is now **{{ ucfirst($order->status) }}**.

@if ($order->status_note)
> {{ $order->status_note }}
@endif

@component('mail::panel')
**Order #:** {{ $order->tracking_number }}  
**Status:** {{ ucfirst($order->status) }}  
**Updated:** {{ optional($order->status_updated_at)->toDateTimeString() }}
@endcomponent

## Items
@foreach ($items as $item)
@php
    $parts = array_filter([
        $item['title'],
        $item['size'] ? 'Size: '.$item['size'] : null,
        $item['color'] ? 'Color: '.$item['color'] : null,
        'Qty: '.$item['qty'],
        'Line: '.sprintf('%s %.2f', strtoupper($currency), (float) $item['line_total']),
    ]);
@endphp
- {{ implode(' | ', $parts) }}
@endforeach

@component('mail::button', ['url' => config('app.url')])
View Order
@endcomponent

Thanks,  
{{ config('app.name') }}
@endcomponent
