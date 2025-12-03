@php
    $shipping = $address->shipping_address ?? [];
    $currency = $address->currency_code ?? 'GBP';
    $tracking = $orderLines->first()->tracking_number ?? null;
    $statusColors = [
        'succeeded' => '#10B981',
        'failed' => '#EF4444',
        'pending' => '#F59E0B',
    ];
    $statusColor = $statusColors[$status] ?? '#6B7280';
    $logoUrl = url('https://main.d36wz4kndhn356.amplifyapp.com/_nuxt/logo.CPcPtLaP.png');
@endphp
<!DOCTYPE html>
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status - {{ config('app.name') }}</title>
    <!--[if mso]><xml><w:WordDocument xmlns:w="urn:schemas-microsoft-com:office:word"><w:DontUseAdvancedTypographyReadingMail/></w:WordDocument><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
    <!--[if !mso]><!-->
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet" type="text/css">
    <!--<![endif]-->
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            -webkit-text-size-adjust: none;
            text-size-adjust: none;
        }

        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: inherit !important;
        }

        #MessageViewBody a {
            color: inherit;
            text-decoration: none;
        }

        p {
            line-height: inherit;
        }

        .row-content {
            border-radius: 0;
            color: #000000;
            width: 600px;
            margin: 0 auto;
        }

        .column {
            font-weight: 400;
            text-align: left;
            vertical-align: top;
        }

        .heading_block h1 {
            margin: 0;
            font-family: 'Anton', sans-serif;
            font-size: 48px;
            font-weight: 700;
            color: #000000;
            line-height: 1.2;
        }

        .heading_block h2 {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            font-size: 32px;
            font-weight: 700;
            color: #000000;
            line-height: 1.2;
        }

        .heading_block h3 {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            font-weight: 600;
            color: #000000;
            line-height: 1.2;
        }

        .paragraph {
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            color: #000000;
            line-height: 1.5;
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
            font-family: 'Poppins', sans-serif;
        }

        .divider_inner {
            font-size: 1px;
            line-height: 1px;
            border-top: 1px solid #000000;
        }

        .item-title {
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: #000000;
        }

        .item-meta {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #6b7280;
        }

        .item-price {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: #000000;
            font-size: 16px;
        }

        .spacer {
            height: 24px;
            line-height: 24px;
            font-size: 1px;
        }

        @media only screen and (max-width:620px) {
            .row-content {
                width: 100% !important;
            }

            .heading_block h1 {
                font-size: 32px !important;
            }

            .heading_block h2 {
                font-size: 24px !important;
            }

            .paragraph {
                font-size: 15px !important;
            }
        }
    </style>
    <!--[if mso]><style>sup, sub { font-size: 100% !important; } sup { mso-text-raise:10% } sub { mso-text-raise:-10% }</style><![endif]-->
</head>

<body style="background-color:#ffffff; margin:0; padding:0; -webkit-text-size-adjust:none; text-size-adjust:none;">
    <table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation"
        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff;">
        <tbody>
            <tr>
                <td>
                    <!-- Hero Section -->
                    <table class="row row-2" align="center" width="100%" border="0" cellpadding="0"
                        cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                        <tbody>
                            <tr>
                                <td>
                                    <table class="row-content stack" align="center" border="0" cellpadding="0"
                                        cellspacing="0" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; border-radius: 0; color: #000000; width: 600px; margin: 0 auto;"
                                        width="600">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" width="100%"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;">
                                                    <table class="image_block block-1" width="100%" border="0"
                                                        cellpadding="0" cellspacing="0" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="width:100%;padding-right:0px;padding-left:0px;">
                                                                <div class="alignment" align="center">
                                                                    <div class="fullWidth" style="max-width: 600px;">
                                                                        <img src="https://api.testicool.co.uk/assets/images/logo.png"
                                                                            style="display: block; height: auto; border: 0; width: 100%;"
                                                                            width="600" alt="Payment Status"
                                                                            title="Payment Status" height="auto">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Main Content Section -->
                    <table class="row row-3" align="center" width="100%" border="0" cellpadding="0"
                        cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                        <tbody>
                            <tr>
                                <td>
                                    <table class="row-content stack" align="center" border="0" cellpadding="0"
                                        cellspacing="0" role="presentation"
                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; border-radius: 0; color: #000000; width: 600px; margin: 0 auto;"
                                        width="600">
                                        <tbody>
                                            <tr>
                                                <td class="column column-1" width="100%"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 48px; padding-left: 50px; padding-right: 50px; padding-top: 48px; vertical-align: top;">
                                                    <table class="heading_block block-1" width="100%" border="0"
                                                        cellpadding="0" cellspacing="0" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad" style="text-align:center;width:100%;">
                                                                <h1
                                                                    style="margin: 0; color: #000000; direction: ltr; font-family: 'Anton', sans-serif; font-size: 48px; font-weight: 600; letter-spacing: normal; line-height: 1.2; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 58px;">
                                                                    Payment Status
                                                                </h1>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table class="paragraph_block block-2" width="100%" border="0"
                                                        cellpadding="0" cellspacing="0" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:20px;padding-top:20px;">
                                                                <div
                                                                    style="color:#000000;direction:ltr;font-family:'Poppins', sans-serif;font-size:18px;font-weight:400;letter-spacing:0px;line-height:1.5;text-align:center;mso-line-height-alt:27px;">
                                                                    <p style="margin: 0;">Hi
                                                                        {{ $address->customer_name }},
                                                                        @if ($status === 'succeeded')
                                                                            we've received your payment!
                                                                        @else
                                                                            there was an issue with your payment.
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <!-- Status Badge -->
                                                    <table width="100%" border="0" cellpadding="0"
                                                        cellspacing="0" role="presentation"
                                                        style="margin-bottom:24px;">
                                                        <tr>
                                                            <td style="text-align:center;">
                                                                <span
                                                                    class="status-badge">{{ strtoupper($status) }}</span>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <!-- Order & Payment Information -->
                                                    <table width="100%" border="0" cellpadding="0"
                                                        cellspacing="0" role="presentation"
                                                        style="border:1px solid #e5e7eb; padding:16px; border-radius:8px; margin-bottom:24px;">
                                                        <tr>
                                                            <td
                                                                style="padding:8px 0; font-family:'Poppins', sans-serif; font-weight:600;">
                                                                Order Number:</td>
                                                            <td
                                                                style="padding:8px 0; text-align:right; font-family:'Poppins', sans-serif; color:#6b7280;">
                                                                #{{ $address->order_no }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="padding:8px 0; font-family:'Poppins', sans-serif; font-weight:600;">
                                                                Tracking Number:</td>
                                                            <td
                                                                style="padding:8px 0; text-align:right; font-family:'Poppins', sans-serif; color:#6b7280;">
                                                                {{ $tracking ?? 'TBC' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="padding:8px 0; font-family:'Poppins', sans-serif; font-weight:600;">
                                                                Payment Reference:</td>
                                                            <td
                                                                style="padding:8px 0; text-align:right; font-family:'Poppins', sans-serif; color:#6b7280;">
                                                                {{ $payment->provider_ref ?? 'N/A' }}</td>
                                                        </tr>
                                                    </table>

                                                    <!-- Payment Summary -->
                                                    <table width="100%" border="0" cellpadding="0"
                                                        cellspacing="0" role="presentation"
                                                        style="padding:12px; border-radius:8px; margin-bottom:24px;">
                                                        <tr>
                                                            <td
                                                                style="padding:6px 0; font-family:'Poppins', sans-serif;">
                                                                Subtotal:</td>
                                                            <td
                                                                style="padding:6px 0; text-align:right; font-family:'Poppins', sans-serif;">
                                                                {{ sprintf('%s %.2f', strtoupper($currency), (float) $address->subtotal) }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="padding:6px 0; font-family:'Poppins', sans-serif;">
                                                                Shipping:</td>
                                                            <td
                                                                style="padding:6px 0; text-align:right; font-family:'Poppins', sans-serif;">
                                                                {{ sprintf('%s %.2f', strtoupper($currency), (float) $address->shipping_total) }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="padding:6px 0; font-family:'Poppins', sans-serif;">
                                                                Discount:</td>
                                                            <td
                                                                style="padding:6px 0; text-align:right; font-family:'Poppins', sans-serif;">
                                                                {{ sprintf('-%s %.2f', strtoupper($currency), (float) $address->discount_total) }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="padding:6px 0; font-family:'Poppins', sans-serif;">
                                                                Tax:</td>
                                                            <td
                                                                style="padding:6px 0; text-align:right; font-family:'Poppins', sans-serif;">
                                                                {{ sprintf('%s %.2f', strtoupper($currency), (float) $address->tax_total) }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="padding-top:12px; border-top:1px solid #e5e7eb; font-family:'Anton', sans-serif; font-weight:700;">
                                                                Total Paid:</td>
                                                            <td
                                                                style="padding-top:12px; border-top:1px solid #e5e7eb; text-align:right; font-family:'Poppins', sans-serif; font-weight:700;">
                                                                {{ sprintf('%s %.2f', strtoupper($currency), (float) $address->grand_total) }}
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <!-- Order Items -->
                                                    <h3
                                                        style="font-family:'Poppins', sans-serif; font-size:18px; font-weight:600; color:#000000; margin:32px 0 16px 0;">
                                                        Items</h3>

                                                    @foreach ($orderLines as $line)
                                                        @php
                                                            $meta = $line->meta ?? [];
                                                            $variant = $meta['variant'] ?? [];
                                                        @endphp
                                                        <table width="100%" border="0" cellpadding="0"
                                                            cellspacing="0" role="presentation"
                                                            style="border-bottom:1px solid #e5e7eb; padding:12px 0; margin-bottom:12px;">
                                                            <tr>
                                                                <td style="vertical-align:top;">
                                                                    <div class="item-title">
                                                                        {{ $line->title_snapshot }}</div>
                                                                    @if (isset($variant['size']) || isset($variant['color']))
                                                                        <div class="item-meta">
                                                                            @if (isset($variant['size']))
                                                                                <span>Size:
                                                                                    {{ $variant['size'] }}</span>
                                                                            @endif
                                                                            @if (isset($variant['size']) && isset($variant['color']))
                                                                                <span> | </span>
                                                                            @endif
                                                                            @if (isset($variant['color']))
                                                                                <span>Color:
                                                                                    {{ $variant['color'] }}</span>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                    <div class="item-meta">Quantity:
                                                                        {{ $line->qty }}</div>
                                                                    @if (!empty($line->image_url))
                                                                        <div class="item-meta"
                                                                            style="font-size:12px; margin-top:6px;"><a
                                                                                href="{{ $line->image_url }}"
                                                                                style="color:#6b7280; text-decoration:none;">View
                                                                                Image</a></div>
                                                                    @endif
                                                                </td>
                                                                <td
                                                                    style="width:120px; text-align:right; vertical-align:top;">
                                                                    <div class="item-price">
                                                                        {{ strtoupper($currency) }}
                                                                        {{ number_format((float) $line->line_total, 2) }}
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    @endforeach

                                                    <!-- Shipping Address -->
                                                    <h3
                                                        style="font-family:'Poppins', sans-serif; font-size:18px; font-weight:600; color:#000000; margin:32px 0 16px 0;">
                                                        Shipping To</h3>
                                                    <table width="100%" border="0" cellpadding="0"
                                                        cellspacing="0" role="presentation"
                                                        style="border:1px solid #e5e7eb; padding:16px; border-radius:8px;">
                                                        <tr>
                                                            <td
                                                                style="font-family:'Poppins', sans-serif; font-size:14px; color:#000000; line-height:1.8;">
                                                                <strong>{{ trim(($shipping['firstName'] ?? '') . ' ' . ($shipping['lastName'] ?? '')) }}</strong><br>
                                                                {{ $shipping['address1'] ?? '' }}<br>
                                                                @if (!empty($shipping['address2']))
                                                                    {{ $shipping['address2'] }}<br>
                                                                @endif
                                                                {{ $shipping['city'] ?? '' }}
                                                                {{ $shipping['state'] ?? '' }}
                                                                {{ $shipping['postal'] ?? '' }}<br>
                                                                {{ $shipping['country'] ?? '' }}<br>
                                                                @if (!empty($shipping['phone']))
                                                                    <br><strong>Phone:</strong>
                                                                    {{ $shipping['phone'] }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <div class="spacer"></div>

                                                    <!-- Footer Text -->
                                                    <table width="100%" border="0" cellpadding="0"
                                                        cellspacing="0" role="presentation">
                                                        <tr>
                                                            <td
                                                                style="padding-top:24px; text-align:center; font-family:'Poppins', sans-serif; font-size:14px; color:#6b7280; line-height:1.6;">
                                                                Thank you for your purchase! If you have any questions,
                                                                please contact our support team.
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
