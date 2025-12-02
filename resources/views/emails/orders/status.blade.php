@php
    $currency = $order->parent?->address?->currency_code ?? ($order->currency_code ?? 'GBP');
    $statusColors = [
        'pending' => '#F59E0B',
        'processing' => '#3B82F6',
        'shipped' => '#8B5CF6',
        'delivered' => '#10B981',
        'cancelled' => '#EF4444',
    ];
    $statusColor = $statusColors[$order->status] ?? '#6B7280';
    $logoUrl = url('https://main.d36wz4kndhn356.amplifyapp.com/_nuxt/logo.CPcPtLaP.png');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update - {{ config('app.name') }}</title>
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

            .items-title {
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
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Header with gradient like frontend banner */
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

        .status-badge {
            display: inline-block;
            background-color: {{ $statusColor }};
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

        .items-section {
            margin: 32px 0;
        }

        .items-title {
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

        .note-box {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1px solid #f59e0b;
            border-left: 3px solid #f59e0b;
            padding: 20px 24px;
            margin: 24px 0;
            border-radius: 6px;
            font-size: 14px;
            color: #78350f;
            box-shadow: none;
        }

        .note-box strong {
            font-weight: 700;
        }

        .button {
            display: inline-block;
            background-color: #000000;
            color: #ffffff !important;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 700;
            font-size: 14px;
            margin: 30px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .button:hover {
            background-color: #1f2937;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
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

        .footer-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #374151, transparent);
            margin: 24px 0;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 24px;
        }

        .footer-link {
            color: #9ca3af;
            text-decoration: underline;
            font-size: 13px;
        }

        .footer-link:hover {
            color: #ffffff;
        }

        .divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #e5e7eb, transparent);
            margin: 40px 0;
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
        <!-- Top Banner (like frontend) -->
        <div class="top-banner">
            Best Men's Underwear - Order Status Update
        </div>

        <!-- Header with Logo -->
        <div class="header">
            <img src="https://api.testicool.co.uk/assets/images/logo.png" alt="TESTICOOL" width="200">
            <div class="hero-title">Order Status Update</div>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">Hi {{ $order->customer_name ?? 'Customer' }},</div>

            <div class="message">
                Your order has been updated! Here's the latest status of your order.
            </div>

            <div style="text-align: center;">
                <span class="status-badge">{{ ucfirst($order->status) }}</span>
            </div>

            @if ($order->status_note)
                <div class="note-box">
                    <strong>📌 Note:</strong> {{ $order->status_note }}
                </div>
            @endif

            <!-- Order Information -->
            <div class="order-info">
                <div class="order-info-row">
                    <span class="order-info-label">Order Number:</span>
                    <span class="order-info-value">#{{ $order->tracking_number }}</span>
                </div>
                <div class="order-info-row">
                    <span class="order-info-label">Status:</span>
                    <span class="order-info-value">{{ ucfirst($order->status) }}</span>
                </div>
                <div class="order-info-row">
                    <span class="order-info-label">Updated:</span>
                    <span
                        class="order-info-value">{{ optional($order->status_updated_at)->format('M d, Y h:i A') }}</span>
                </div>
            </div>

            <!-- Order Items -->
            <div class="items-section">
                <div class="items-title">Order Items</div>
                @foreach ($items as $item)
                    <div class="item">
                        <div class="item-details">
                            <div class="item-title">{{ $item['title'] }}</div>
                            @if ($item['size'] || $item['color'])
                                <div class="item-meta">
                                    @if ($item['size'])
                                        <span>Size: {{ $item['size'] }}</span>
                                    @endif
                                    @if ($item['size'] && $item['color'])
                                        <span> | </span>
                                    @endif
                                    @if ($item['color'])
                                        <span>Color: {{ $item['color'] }}</span>
                                    @endif
                                </div>
                            @endif
                            <div class="item-meta">Quantity: {{ $item['qty'] }}</div>
                        </div>
                        <div class="item-price">
                            {{ strtoupper($currency) }} {{ number_format((float) $item['line_total'], 2) }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="divider"></div>

            <!-- Call to Action -->
            <div style="text-align: center;">
                <a href="{{ config('app.url') }}" class="button">View Order Details</a>
            </div>

            <div class="message" style="text-align: center; margin-top: 30px; font-size: 14px;">
                If you have any questions about your order, please don't hesitate to contact us at
                <strong>support@testicool.co.uk</strong>
            </div>
        </div>

        <!-- Footer (matching frontend footer) -->
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
