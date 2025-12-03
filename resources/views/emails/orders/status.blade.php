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
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update - {{ config('app.name') }}</title>
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

        .desktop_hide,
        .desktop_hide table {
            mso-hide: all;
            display: none;
            max-height: 0px;
            overflow: hidden;
        }

        .image_block img+div {
            display: none;
        }

        sup,
        sub {
            font-size: 75%;
            line-height: 0;
        }

        @media (max-width:620px) {

            .desktop_hide table.icons-inner,
            .social_block.desktop_hide .social-table {
                display: inline-block !important;
            }

            .icons-inner {
                text-align: center;
            }

            .icons-inner td {
                margin: 0 auto;
            }

            .image_block div.fullWidth {
                max-width: 100% !important;
            }

            .mobile_hide {
                display: none;
            }

            .row-content {
                width: 100% !important;
            }

            .stack .column {
                width: 100%;
                display: block;
            }

            .mobile_hide {
                min-height: 0;
                max-height: 0;
                max-width: 0;
                overflow: hidden;
                font-size: 0px;
            }

            .desktop_hide,
            .desktop_hide table {
                display: table !important;
                max-height: none !important;
            }
        }

        table {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        }

        .row-content {
            border-radius: 0;
            color: #000000;
            width: 600px;
            margin: 0 auto;
        }

        .row-content.stack {
            background-color: #ffffff;
        }

        .column {
            font-weight: 400;
            text-align: left;
            vertical-align: top;
        }

        .heading_block h1,
        .heading_block h2,
        .heading_block h3 {
            margin: 0;
            color: #000000;
            direction: ltr;
            letter-spacing: normal;
            line-height: 1.2;
            margin-top: 0;
            margin-bottom: 0;
        }

        .heading_block h1 {
            font-family: 'Anton', sans-serif;
            font-size: 48px;
            font-weight: 700;
        }

        .heading_block h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 32px;
            font-weight: 700;
        }

        .heading_block h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            font-weight: 600;
        }

        .paragraph_block div {
            color: #000000;
            direction: ltr;
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            font-weight: 400;
            letter-spacing: 0px;
            line-height: 1.5;
        }

        .button_block .alignment {
            text-align: center;
        }

        .button_block .button {
            background-color: #000000;
            border-radius: 10px;
            color: #ffffff;
            display: inline-block;
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: 400;
            padding: 12px 24px;
            text-align: center;
            text-decoration: none;
            letter-spacing: normal;
            line-height: 24px;
        }

        .divider_inner {
            font-size: 1px;
            line-height: 1px;
            border-top: 1px solid #000000;
        }

        .spacer_block {
            height: 24px;
            line-height: 24px;
            font-size: 1px;
        }

        .status-badge {
            display: inline-block;
            background-color: #000000;
            color: #ffffff;
            padding: 6px 16px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        }

        .footer {
            background-color: #ffffff;
            color: #000000;
            padding: 60px 20px;
            text-align: center;
        }

        .footer-text {
            color: #000000;
            font-family: 'Anton', sans-serif;
            font-size: 14px;
            font-weight: 400;
            letter-spacing: 0px;
            line-height: 1.5;
        }

        @media only screen and (max-width: 620px) {
            .row-content {
                width: 100% !important;
            }

            .column {
                width: 100% !important;
                display: block;
            }

            .heading_block h1 {
                font-size: 32px !important;
            }

            .heading_block h2 {
                font-size: 24px !important;
            }

            .paragraph_block div {
                font-size: 16px !important;
            }
        }
    </style>
    <!--[if mso]><style>sup, sub { font-size: 100% !important; } sup { mso-text-raise:10% } sub { mso-text-raise:-10% }</style><![endif]-->
</head>

<body class="body"
    style="background-color: #ffffff; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
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
                                                                            width="600" alt="Order Status"
                                                                            title="Order Status" height="auto">
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
                                                                    Order Status Update
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
                                                                        {{ $order->customer_name ?? 'Customer' }}, your
                                                                        order has been updated!</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table class="button_block block-3" width="100%" border="0"
                                                        cellpadding="0" cellspacing="0" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-left:10px;padding-right:10px;padding-top:10px;text-align:center;">
                                                                <div class="alignment" align="center">
                                                                    <span
                                                                        class="status-badge">{{ ucfirst($order->status) }}</span>
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

                    <!-- Spacer -->
                    <table class="row row-4" align="center" width="100%" border="0" cellpadding="0"
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
                                                    <div class="spacer_block block-1"
                                                        style="height:24px;line-height:24px;font-size:1px;">&#8202;
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Order Details Section -->
                    <table class="row row-5" align="center" width="100%" border="0" cellpadding="0"
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
                                                    <table class="heading_block block-1" width="100%"
                                                        border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad" style="text-align:center;width:100%;">
                                                                <h2
                                                                    style="margin: 0; color: #000000; direction: ltr; font-family: 'Anton', sans-serif; font-size: 32px; font-weight: 500; letter-spacing: normal; line-height: 1.2; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 38px;">
                                                                    What's in your order?
                                                                </h2>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table class="paragraph_block block-2" width="100%"
                                                        border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                        <tr>
                                                            <td class="pad" style="padding-top:20px;">
                                                                <div
                                                                    style="color:#000000;direction:ltr;font-family:'Poppins', sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:1.5;text-align:center;mso-line-height-alt:24px;">
                                                                    <p style="margin: 0;">Order Number:
                                                                        #{{ $order->tracking_number }}</p>
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

                    <!-- Order Items -->
                    @foreach ($items as $item)
                        <table class="row row-6" align="center" width="100%" border="0" cellpadding="0"
                            cellspacing="0" role="presentation"
                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0"
                                            cellpadding="0" cellspacing="0" role="presentation"
                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; border-radius: 0; color: #000000; width: 600px; margin: 0 auto;"
                                            width="600">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="33.333333333333336%"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-left: 50px; padding-right: 12px; vertical-align: top;">
                                                        <table class="image_block block-1" width="100%"
                                                            border="0" cellpadding="0" cellspacing="0"
                                                            role="presentation"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad"
                                                                    style="width:100%;padding-right:0px;padding-left:0px;">
                                                                    <div class="alignment" align="center">
                                                                        <div class="fullWidth"
                                                                            style="max-width: 138px;">
                                                                            @if (isset($item['image']) && $item['image'])
                                                                                <img src="{{ $item['image'] }}"
                                                                                    style="display: block; height: auto; border: 0; width: 100%;"
                                                                                    width="138"
                                                                                    alt="{{ $item['title'] }}"
                                                                                    title="{{ $item['title'] }}"
                                                                                    height="auto">
                                                                            @else
                                                                                <img src="https://via.placeholder.com/138"
                                                                                    style="display: block; height: auto; border: 0; width: 100%;"
                                                                                    width="138"
                                                                                    alt="{{ $item['title'] }}"
                                                                                    title="{{ $item['title'] }}"
                                                                                    height="auto">
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td class="column column-2" width="41.666666666666664%"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-left: 12px; padding-right: 24px; padding-top: 24px; vertical-align: top;">
                                                        <table class="heading_block block-1" width="100%"
                                                            border="0" cellpadding="0" cellspacing="0"
                                                            role="presentation"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad"
                                                                    style="padding-bottom:15px;text-align:center;width:100%;">
                                                                    <h3
                                                                        style="margin: 0; color: #000000; direction: ltr; font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 600; letter-spacing: normal; line-height: 1.2; text-align: left; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 22px;">
                                                                        {{ $item['title'] }}
                                                                    </h3>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table class="paragraph_block block-2" width="100%"
                                                            border="0" cellpadding="0" cellspacing="0"
                                                            role="presentation"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad" style="padding-bottom:5px;">
                                                                    <div
                                                                        style="color:#000000;direction:ltr;font-family:'Poppins', sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:1.5;text-align:left;mso-line-height-alt:21px;">
                                                                        <p style="margin: 0;">
                                                                            @if ($item['size'])
                                                                                Size: {{ $item['size'] }}
                                                                            @endif
                                                                            @if ($item['size'] && $item['color'])
                                                                                |
                                                                            @endif
                                                                            @if ($item['color'])
                                                                                Color: {{ $item['color'] }}
                                                                            @endif
                                                                        </p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <table class="paragraph_block block-3" width="100%"
                                                            border="0" cellpadding="0" cellspacing="0"
                                                            role="presentation"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad">
                                                                    <div
                                                                        style="color:#000000;direction:ltr;font-family:'Poppins', sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:1.5;text-align:left;mso-line-height-alt:21px;">
                                                                        <p style="margin: 0;">Quantity:
                                                                            {{ $item['qty'] }}</p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td class="column column-3" width="25%"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-right: 50px; padding-top: 24px; vertical-align: top;">
                                                        <table class="heading_block block-1" width="100%"
                                                            border="0" cellpadding="0" cellspacing="0"
                                                            role="presentation"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                            <tr>
                                                                <td class="pad"
                                                                    style="padding-bottom:15px;text-align:center;width:100%;">
                                                                    <h3
                                                                        style="margin: 0; color: #000000; direction: ltr; font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 700; letter-spacing: normal; line-height: 1.2; text-align: right; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 22px;">
                                                                        {{ strtoupper($currency) }}
                                                                        {{ number_format((float) $item['line_total'], 2) }}
                                                                    </h3>
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

                        <!-- Spacer between items -->
                        <table class="row row-7" align="center" width="100%" border="0" cellpadding="0"
                            cellspacing="0" role="presentation"
                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0"
                                            cellpadding="0" cellspacing="0" role="presentation"
                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; border-radius: 0; color: #000000; width: 600px; margin: 0 auto;"
                                            width="600">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top;">
                                                        <div class="spacer_block block-1"
                                                            style="height:24px;line-height:24px;font-size:1px;">&#8202;
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endforeach

                    <!-- Status Note (if exists) -->
                    @if ($order->status_note)
                        <table class="row" align="center" width="100%" border="0" cellpadding="0"
                            cellspacing="0" role="presentation"
                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                            <tbody>
                                <tr>
                                    <td>
                                        <table class="row-content stack" align="center" border="0"
                                            cellpadding="0" cellspacing="0" role="presentation"
                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; border-radius: 0; color: #000000; width: 600px; margin: 0 auto;"
                                            width="600">
                                            <tbody>
                                                <tr>
                                                    <td class="column column-1" width="100%"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-left: 50px; padding-right: 50px; padding-bottom: 24px; vertical-align: top;">
                                                        <table class="paragraph_block block-1" width="100%"
                                                            border="0" cellpadding="0" cellspacing="0"
                                                            role="presentation"
                                                            style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                            <tr>
                                                                <td class="pad"
                                                                    style="padding: 20px 24px; background-color: #ffffff; border: 1px solid #000000; border-left: 3px solid #000000; border-radius: 6px;">
                                                                    <div
                                                                        style="color:#000000;direction:ltr;font-family:'Poppins', sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:1.5;text-align:left;mso-line-height-alt:21px;">
                                                                        <p style="margin: 0;"><strong>📌 Note:</strong>
                                                                            {{ $order->status_note }}</p>
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
                    @endif

                    <!-- Divider -->
                    <table class="row row-11" align="center" width="100%" border="0" cellpadding="0"
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
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-left: 50px; padding-right: 50px; vertical-align: top;">
                                                    <table class="divider_block block-1" width="100%"
                                                        border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:40px;padding-top:40px;">
                                                                <div class="alignment" align="center">
                                                                    <table border="0" cellpadding="0"
                                                                        cellspacing="0" role="presentation"
                                                                        width="100%"
                                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                        <tr>
                                                                            <td class="divider_inner"
                                                                                style="font-size: 1px; line-height: 1px; border-top: 1px solid #000000;">
                                                                                <span
                                                                                    style="word-break: break-word;">&#8202;</span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
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

                    <!-- Order Information Details -->
                    <table class="row row-16" align="center" width="100%" border="0" cellpadding="0"
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
                                                <td class="column column-1" width="50%"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-left: 50px; padding-right: 20px; vertical-align: top;">
                                                    <table class="divider_block block-1" width="100%"
                                                        border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:40px;padding-top:40px;">
                                                                <div class="alignment" align="center">
                                                                    <table border="0" cellpadding="0"
                                                                        cellspacing="0" role="presentation"
                                                                        width="100%"
                                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                        <tr>
                                                                            <td class="divider_inner"
                                                                                style="font-size: 1px; line-height: 1px; border-top: 1px solid #000000;">
                                                                                <span
                                                                                    style="word-break: break-word;">&#8202;</span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table class="heading_block block-2" width="100%"
                                                        border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:5px;text-align:center;width:100%;">
                                                                <h3
                                                                    style="margin: 0; color: #000000; direction: ltr; font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 600; letter-spacing: normal; line-height: 1.2; text-align: left; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 22px;">
                                                                    Order Status:
                                                                </h3>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table class="heading_block block-3" width="100%"
                                                        border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:5px;padding-top:10px;text-align:center;width:100%;">
                                                                <h3
                                                                    style="margin: 0; color: #5d5d60; direction: ltr; font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 400; letter-spacing: normal; line-height: 1.2; text-align: left; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 17px;">
                                                                    {{ ucfirst($order->status) }}
                                                                </h3>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table class="heading_block block-4" width="100%"
                                                        border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:10px;padding-top:10px;text-align:center;width:100%;">
                                                                <h3
                                                                    style="margin: 0; color: #5d5d60; direction: ltr; font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 400; letter-spacing: normal; line-height: 1.2; text-align: left; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 17px;">
                                                                    Updated:
                                                                    {{ optional($order->status_updated_at)->format('F d, Y') }}
                                                                </h3>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td class="column column-2" width="50%"
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-left: 20px; padding-right: 50px; vertical-align: top;">
                                                    <table class="divider_block block-1" width="100%"
                                                        border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:40px;padding-top:40px;">
                                                                <div class="alignment" align="center">
                                                                    <table border="0" cellpadding="0"
                                                                        cellspacing="0" role="presentation"
                                                                        width="100%"
                                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                        <tr>
                                                                            <td class="divider_inner"
                                                                                style="font-size: 1px; line-height: 1px; border-top: 1px solid #000000;">
                                                                                <span
                                                                                    style="word-break: break-word;">&#8202;</span>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table class="heading_block block-2" width="100%"
                                                        border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:5px;text-align:center;width:100%;">
                                                                <h3
                                                                    style="margin: 0; color: #000000; direction: ltr; font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 600; letter-spacing: normal; line-height: 1.2; text-align: left; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 22px;">
                                                                    Tracking Number:
                                                                </h3>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table class="heading_block block-3" width="100%"
                                                        border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:5px;padding-top:10px;text-align:center;width:100%;">
                                                                <h3
                                                                    style="margin: 0; color: #5d5d60; direction: ltr; font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 400; letter-spacing: normal; line-height: 1.2; text-align: left; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 17px;">
                                                                    #{{ $order->tracking_number }}
                                                                </h3>
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

                    <!-- Track Order Button -->
                    <table class="row row-18" align="center" width="100%" border="0" cellpadding="0"
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
                                                    <table class="button_block block-1" width="100%" border="0"
                                                        cellpadding="0" cellspacing="0" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-left:10px;padding-right:10px;text-align:center;">
                                                                <div class="alignment" align="center">
                                                                    <a href="{{ config('app.url') }}" target="_blank"
                                                                        style="color:#ffffff;text-decoration:none;">
                                                                        <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ config('app.url') }}" style="height:48px;width:153px;v-text-anchor:middle;" arcsize="21%" fillcolor="#000000"><v:stroke dashstyle="Solid" weight="0px" color="#000000"/><w:anchorlock/><v:textbox inset="0px,0px,0px,0px"><center dir="false" style="color:#ffffff;font-family:sans-serif;font-size:16px"><![endif]-->
                                                                        <span class="button"
                                                                            style="background-color: #000000; border-radius: 10px; color: #ffffff; display: inline-block; font-family: 'Poppins', sans-serif; font-size: 16px; font-weight: 400; padding-bottom: 12px; padding-top: 12px; padding-left: 24px; padding-right: 24px; text-align: center; text-decoration: none; letter-spacing: normal;">
                                                                            <span style="line-height: 24px;">View Order
                                                                                Details</span>
                                                                        </span>
                                                                        <!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
                                                                    </a>
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

                    <!-- Spacer -->
                    <table class="row row-19" align="center" width="100%" border="0" cellpadding="0"
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
                                                    <div class="spacer_block block-1"
                                                        style="height:24px;line-height:24px;font-size:1px;">&#8202;
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Footer -->
                    <table class="row row-23" align="center" width="100%" border="0" cellpadding="0"
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
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 60px; padding-top: 60px; vertical-align: top;">
                                                    <table class="image_block block-1" width="100%" border="0"
                                                        cellpadding="0" cellspacing="0" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="width:100%;padding-right:0px;padding-left:0px;">
                                                                <div class="alignment" align="center">
                                                                    <div style="max-width: 120px;">
                                                                        <img src="https://api.testicool.co.uk/assets/images/logo.png"
                                                                            style="display: block; height: auto; border: 0; width: 100%;"
                                                                            width="120" alt="TESTICOOL"
                                                                            title="TESTICOOL" height="auto">
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table class="paragraph_block block-2" width="100%"
                                                        border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:15px;padding-top:15px;">
                                                                <div
                                                                    style="color:#000000;direction:ltr;font-family:'Poppins', sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:1.5;text-align:center;mso-line-height-alt:21px;">
                                                                    <p style="margin: 0;">© {{ date('Y') }}
                                                                        TestiCool. All rights reserved.<br>Performance
                                                                        essentials engineered for comfort and
                                                                        confidence.</p>
                                                                    <p style="margin: 0; margin-top: 10px;">📧 <a
                                                                            href="mailto:support@testicool.co.uk"
                                                                            style="color: #000000; text-decoration: underline;">support@testicool.co.uk</a>
                                                                    </p>
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
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
