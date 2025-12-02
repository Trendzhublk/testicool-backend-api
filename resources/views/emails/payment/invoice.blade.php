@php
    $shipping = $address->shipping_address ?? [];
    $currency = $address->currency_code ?? 'GBP';
    $tracking = $orderLines->first()->tracking_number ?? null;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice for Order {{ $address->order_no }} - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Inter:wght@400;600;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #111827;
            line-height: 1.6;
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .greeting {
                color: #ffffff !important;
            }

            .message {
                color: #a3a3a3 !important;
            }

            .order-info-label {
                color: #e5e5e5 !important;
            }

            .order-info-value {
                color: #a3a3a3 !important;
            }

            .section-title {
                color: #ffffff !important;
            }

            .item-title {
                color: #ffffff !important;
            }

            .item-meta {
                color: #737373 !important;
            }

            .item-price {
                color: #e5e5e5 !important;
            }

            .total-label,
            .total-value {
                color: #d4d4d4 !important;
            }

            .grand-total-label,
            .grand-total-value {
                color: #ffffff !important;
            }

            .shipping-title {
                color: #e5e5e5 !important;
            }

            .shipping-info {
                color: #a3a3a3 !important;
            }

            .header {
                background-color: #000000;
                padding: 30px 0;
                text-align: center;
                border-bottom: 1px solid #1f2937;
            }
        }

        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            padding: 0 15px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .top-banner {
            background: linear-gradient(to right, #0f172a, #334155);
            color: #ffffff;
            text-align: center;
            padding: 12px 20px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .header {
            background-color: #000000;
            padding: 30px 0;
            text-align: center;
            border-bottom: 1px solid #1f2937;
        }

        .logo {

            display: block;
            margin: 0 auto;
        }

        .hero-title {
            font-family: 'Anton', sans-serif;
            font-size: 28px;
            color: #ffffff;
            margin-top: 16px;
            padding: 0 20px;
            letter-spacing: 1px;
        }

        .content {
            padding: 40px 30px;
            background-color: #ffffff;

        }

        .greeting {
            font-size: 20px;
            font-weight: 700;
            color: #000000;
            margin-bottom: 12px;
            font-family: 'Inter', sans-serif;
        }

        .message {
            font-size: 15px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .payment-badge {
            display: inline-block;
            background-color: #10B981;
            color: #ffffff;
            padding: 6px 16px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
            box-shadow: none;
        }

        .order-info {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-left: 3px solid #000000;
            padding: 20px 24px;
            margin: 24px 0;
            border-radius: 6px;
            box-shadow: none;
        }

        .order-info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: none;
        }

        .order-info-row:last-child {
            border-bottom: none;
        }

        .order-info-label {
            font-weight: 600;
            color: #111827;
            font-size: 14px;
        }

        .order-info-value {
            color: #6b7280;
            font-size: 14px;
            text-align: right;
        }

        .section-title {
            font-family: 'Inter', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #000000;
            margin: 32px 0 16px 0;
            padding-bottom: 0;
            border-bottom: none;
            text-transform: none;
            letter-spacing: 0;
        }

        .item {
            background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
            padding: 20px;
            margin-bottom: 16px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .item-details {
            flex: 1;
        }

        .item-title {
            font-weight: 700;
            color: #000000;
            font-size: 16px;
            margin-bottom: 8px;
        }

        .item-meta {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .item-price {
            font-weight: 700;
            color: #000000;
            font-size: 18px;
            white-space: nowrap;
            margin-left: 20px;
        }

        .shipping-box {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #3b82f6;
            border-left: 3px solid #3b82f6;
            padding: 16px 20px;
            margin: 24px 0;
            border-radius: 6px;
            box-shadow: none;
        }

        .shipping-title {
            font-weight: 700;
            color: #1e40af;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .shipping-info {
            color: #1e40af;
            font-size: 13px;
            line-height: 1.6;
        }

        .totals-section {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1px solid #f59e0b;
            border-left: 3px solid #f59e0b;
            padding: 20px 24px;
            margin: 24px 0;
            border-radius: 6px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 14px;
        }

        .total-label {
            color: #78350f;
            font-weight: 400;
        }

        .total-value {
            color: #78350f;
            font-weight: 600;
            text-align: right;
        }

        .grand-total-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0 0 0;
            margin-top: 8px;
            border-top: 2px solid #f59e0b;
            font-size: 18px;
        }

        .grand-total-label {
            color: #000000;
            font-weight: 700;
            font-family: 'Anton', sans-serif;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .grand-total-value {
            color: #000000;
            font-weight: 700;
            font-size: 22px;
        }

        .divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #e5e7eb, transparent);
            margin: 40px 0;
        }

        .footer {
            background-color: #000000;
            color: #ffffff;
            padding: 40px 30px;
        }

        .footer-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 20px;
        }

        .footer-left {
            flex: 1;
        }

        .footer-logo {
            max-width: 150px;
            width: auto;
            height: auto;
            display: block;
            margin-bottom: 12px;
        }

        .footer-message {
            font-size: 14px;
            color: #ffffff;
            font-weight: 600;
            line-height: 1.6;
            max-width: 300px;
        }

        .footer-right {
            text-align: right;
        }

        .footer-text {
            font-size: 14px;
            color: #9ca3af;
            line-height: 1.8;
            margin: 8px 0;
        }

        .footer-link {
            color: #9ca3af;
            text-decoration: underline;
            font-size: 13px;
        }

        .footer-link:hover {
            color: #ffffff;
        }

        .footer-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #374151, transparent);
            margin: 24px 0;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 24px;
        }

        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }

            .content {
                padding: 30px 20px;
            }

            .top-banner {
                font-size: 10px;
                padding: 10px 15px;
            }

            .header {
                padding: 24px 20px;
            }

            .logo {
                max-width: 150px;
            }

            .hero-title {
                font-size: 22px;
            }

            .greeting {
                font-size: 22px;
            }

            .item {
                flex-direction: column;
                align-items: flex-start;
            }

            .item-price {
                margin-left: 0;
                margin-top: 12px;
            }

            .footer-top {
                flex-direction: column;
                align-items: flex-start;
            }

            .footer-left {
                width: 100%;
            }

            .footer-right {
                width: 100%;
                text-align: left;
                margin-top: 20px;
            }

            .footer-logo {
                max-width: 120px;
            }

            .footer-message {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <!-- Top Banner -->
        <div class="top-banner">
            Best Men's Underwear - Payment Invoice
        </div>

        <!-- Header with Logo -->
        <div class="header">
            <img src="https://api.testicool.co.uk/assets/images/logo.png" alt="TESTICOOL" width="200">
            <div class="hero-title">Invoice for Order {{ $address->order_no }}</div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Hi {{ $address->customer_name }},</div>

            <div class="message">
                Thanks for your purchase! Your payment has been received. Below is your invoice for order
                <strong>#{{ $address->order_no }}</strong>.
            </div>

            <!-- Order & Payment Information -->
            <div class="order-info">
                <div class="order-info-row">
                    <span class="order-info-label">Order Number:</span>
                    <span class="order-info-value">#{{ $address->order_no }}</span>
                </div>
                <div class="order-info-row">
                    <span class="order-info-label">Tracking Number:</span>
                    <span class="order-info-value">{{ $tracking ?? 'TBC' }}</span>
                </div>
                <div class="order-info-row">
                    <span class="order-info-label">Payment Status:</span>
                    <span class="order-info-value"><span
                            class="payment-badge">{{ strtoupper($payment->status ?? 'PAID') }}</span></span>
                </div>
                <div class="order-info-row">
                    <span class="order-info-label">Payment Method:</span>
                    <span class="order-info-value">{{ $payment->provider ?? 'Stripe' }}</span>
                </div>
                <div class="order-info-row">
                    <span class="order-info-label">Payment Reference:</span>
                    <span class="order-info-value">{{ $payment->provider_ref ?? 'N/A' }}</span>
                </div>
            </div>

            <!-- Tracking Number -->
            <div class="section-title">Your tracking number</div>
            <div
                style="background-color: #000000; color: #ffffff; padding: 16px 20px; border-radius: 6px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
                <span
                    style="font-family: 'Anton', sans-serif; font-size: 20px; letter-spacing: 1px;">{{ $tracking ?? 'TBC' }}</span>
                @if ($tracking)
                    <a href="#"
                        style="color: #3b82f6; text-decoration: none; font-weight: 600; font-size: 14px;">Go to
                        tracking</a>
                @endif
            </div>

            <!-- Order Items -->
            <div style="display: none;">Order Items</div>
            @foreach ($orderLines as $line)
                @php
                    $meta = $line->meta ?? [];
                    $variant = $meta['variant'] ?? [];
                @endphp
                <div class="item">
                    <div class="item-details">
                        <div class="item-title">{{ $line->title_snapshot }}</div>
                        @if (isset($variant['size']) || isset($variant['color']))
                            <div class="item-meta">
                                @if (isset($variant['size']))
                                    <span>Size: {{ $variant['size'] }}</span>
                                @endif
                                @if (isset($variant['size']) && isset($variant['color']))
                                    <span> | </span>
                                @endif
                                @if (isset($variant['color']))
                                    <span>Color: {{ $variant['color'] }}</span>
                                @endif
                            </div>
                        @endif
                        <div class="item-meta">Quantity: {{ $line->qty }}</div>
                        @if (!empty($line->image_url))
                            <div class="item-meta" style="font-size: 12px; margin-top: 4px;">
                                <a href="{{ $line->image_url }}" style="color: #6b7280;">View Image</a>
                            </div>
                        @endif
                    </div>
                    <div class="item-price">
                        {{ strtoupper($currency) }} {{ number_format((float) $line->line_total, 2) }}
                    </div>
                </div>
            @endforeach

            <!-- Payment Summary -->
            <div class="totals-section">
                <div class="total-row">
                    <span class="total-label">Subtotal:</span>
                    <span
                        class="total-value">{{ sprintf('%s %.2f', strtoupper($currency), (float) $address->subtotal) }}</span>
                </div>
                <div class="total-row">
                    <span class="total-label">Shipping:</span>
                    <span
                        class="total-value">{{ sprintf('%s %.2f', strtoupper($currency), (float) $address->shipping_total) }}</span>
                </div>
                <div class="total-row">
                    <span class="total-label">Discount:</span>
                    <span
                        class="total-value">{{ sprintf('-%s %.2f', strtoupper($currency), (float) $address->discount_total) }}</span>
                </div>
                <div class="total-row">
                    <span class="total-label">Tax:</span>
                    <span
                        class="total-value">{{ sprintf('%s %.2f', strtoupper($currency), (float) $address->tax_total) }}</span>
                </div>
                <div class="grand-total-row">
                    <span class="grand-total-label">Total Paid:</span>
                    <span
                        class="grand-total-value">{{ sprintf('%s %.2f', strtoupper($currency), (float) $address->grand_total) }}</span>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="section-title">Shipping To</div>
            <div class="shipping-box">
                <div class="shipping-title">📦 Delivery Address</div>
                <div class="shipping-info">
                    <strong>{{ trim(($shipping['firstName'] ?? '') . ' ' . ($shipping['lastName'] ?? '')) }}</strong><br>
                    {{ $shipping['address1'] ?? '' }}<br>
                    @if (!empty($shipping['address2']))
                        {{ $shipping['address2'] }}<br>
                    @endif
                    {{ $shipping['city'] ?? '' }} {{ $shipping['state'] ?? '' }} {{ $shipping['postal'] ?? '' }}<br>
                    {{ $shipping['country'] ?? '' }}<br>
                    @if (!empty($shipping['phone']))
                        <br><strong>Phone:</strong> {{ $shipping['phone'] }}
                    @endif
                </div>
            </div>

            <div class="divider"></div>

            <div class="message" style="text-align: center; margin-top: 30px; font-size: 14px;">
                If you have any questions about your invoice or order, please contact us at
                <strong>support@testicool.co.uk</strong>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-top">
                <div class="footer-left">
                    <img src="https://api.testicool.co.uk/assets/images/logo.png" alt="TESTICOOL" class="footer-logo">
                    <div class="footer-message">
                        Performance essentials engineered for comfort and confidence.
                    </div>
                </div>
                <div class="footer-right">
                    <p class="footer-text">
                        📧 <a href="mailto:support@testicool.co.uk" class="footer-link">support@testicool.co.uk</a>
                    </p>
                </div>
            </div>

            <div class="footer-divider"></div>

            <div class="footer-bottom">
                <p class="footer-text" style="font-size: 12px;">
                    © {{ date('Y') }} TestiCool. All rights reserved.
                </p>
                <p class="footer-text" style="font-size: 11px; margin-top: 16px; opacity: 0.7;">
                    This is an automated email. Please do not reply to this message.
                </p>
            </div>
        </div>
    </div>
</body>

</html>
