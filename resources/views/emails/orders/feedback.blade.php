@php
    $customer = $order->customer_name ?? 'there';
    $logoUrl = url('https://main.d36wz4kndhn356.amplifyapp.com/_nuxt/logo.CPcPtLaP.png');
@endphp
<!DOCTYPE html>
<html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Feedback - {{ config('app.name') }}</title>
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

        .row-content {
            border-radius: 0;
            color: #000000;
            width: 600px;
            margin: 0 auto;
        }

        .row-content.stack {
            background-color: #2b2a2e;
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

        .footer {
            background-color: #ffffff;
            color: #000000;
            padding: 60px 20px;
            text-align: center;
        }

        .footer-text {
            color: #000000;
            font-family: 'Poppins', sans-serif;
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
                                                            <td class="pad" style="width:100%;">
                                                                <div class="alignment" align="center"
                                                                    style="line-height:10px">
                                                                    <div style="max-width: 600px;"><img
                                                                            src="{{ $logoUrl }}"
                                                                            style="display: block; height: auto; border: 0; width: 100%;"
                                                                            width="600" alt="Logo" title="Logo"
                                                                            height="auto"></div>
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
                                                                    style="margin: 0; color: #000000; direction: ltr; font-family: 'Anton', sans-serif; font-size: 48px; font-weight: 700; letter-spacing: normal; line-height: 1.2; text-align: center; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 57.6px;">
                                                                    Your Order Arrived! 🎉</h1>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table class="paragraph_block block-2" width="100%" border="0"
                                                        cellpadding="0" cellspacing="0" role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:10px;padding-left:10px;padding-right:10px;padding-top:20px;">
                                                                <div
                                                                    style="color:#000000;direction:ltr;font-family:'Poppins', sans-serif;font-size:18px;font-weight:400;letter-spacing:0px;line-height:1.5;text-align:center;mso-line-height-alt:27px;">
                                                                    <p style="margin: 0;">Hi {{ $customer }}, we
                                                                        hope you love it!</p>
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

                    <!-- Feedback Content Section -->
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
                                                    <table class="paragraph_block block-1" width="100%"
                                                        border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="padding-bottom:10px;padding-left:10px;padding-right:10px;padding-top:10px;">
                                                                <div
                                                                    style="color:#000000;direction:ltr;font-family:'Poppins', sans-serif;font-size:18px;font-weight:400;letter-spacing:0px;line-height:1.5;text-align:left;mso-line-height-alt:27px;">
                                                                    <p style="margin: 0;">We'd love to hear how
                                                                        everything went with order
                                                                        <strong>{{ $order->tracking_number }}</strong>.
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

                    <!-- Divider -->
                    <table class="row row-6" align="center" width="100%" border="0" cellpadding="0"
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
                                                                style="padding-bottom:10px;padding-top:10px;">
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

                    <!-- Order Details -->
                    <table class="row row-7" align="center" width="100%" border="0" cellpadding="0"
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
                                                    style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; padding-bottom: 24px; padding-left: 50px; padding-right: 50px; padding-top: 24px; vertical-align: top;">
                                                    <table class="heading_block block-1" width="100%"
                                                        border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="text-align:left;width:100%;padding-bottom:10px;">
                                                                <h3
                                                                    style="margin: 0; color: #000000; direction: ltr; font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 600; letter-spacing: normal; line-height: 1.2; text-align: left; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 21.6px;">
                                                                    Order #: {{ $order->tracking_number }}</h3>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table class="heading_block block-2" width="100%"
                                                        border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad"
                                                                style="text-align:left;width:100%;padding-bottom:10px;">
                                                                <h3
                                                                    style="margin: 0; color: #000000; direction: ltr; font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 600; letter-spacing: normal; line-height: 1.2; text-align: left; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 21.6px;">
                                                                    Status: Delivered</h3>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <table class="heading_block block-3" width="100%"
                                                        border="0" cellpadding="0" cellspacing="0"
                                                        role="presentation"
                                                        style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tr>
                                                            <td class="pad" style="text-align:left;width:100%;">
                                                                <h3
                                                                    style="margin: 0; color: #000000; direction: ltr; font-family: 'Poppins', sans-serif; font-size: 18px; font-weight: 600; letter-spacing: normal; line-height: 1.2; text-align: left; margin-top: 0; margin-bottom: 0; mso-line-height-alt: 21.6px;">
                                                                    Updated:
                                                                    {{ optional($order->status_updated_at)->toDateTimeString() }}
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

                    <!-- Feedback Button -->
                    <table class="row row-8" align="center" width="100%" border="0" cellpadding="0"
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
                                                                style="padding-bottom:10px;padding-left:10px;padding-right:10px;padding-top:10px;text-align:center;">
                                                                <div class="alignment" align="center">
                                                                    <!--[if mso]>
<v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $feedbackUrl }}" style="height:48px;v-text-anchor:middle;width:194px;" arcsize="21%" stroke="f" fillcolor="#000000">
<w:anchorlock/>
<center style="color:#ffffff;font-family:'Poppins', sans-serif;font-size:16px">
<![endif]-->
                                                                    <a href="{{ $feedbackUrl }}" target="_blank"
                                                                        style="background-color:#000000;border-radius:10px;color:#ffffff;display:inline-block;font-family:'Poppins', sans-serif;font-size:16px;font-weight:400;mso-border-alt:none;padding:12px 24px;text-align:center;text-decoration:none;width:auto;word-break:keep-all;"><span
                                                                            style="word-break: break-word; padding-left: 0px; padding-right: 0px; font-size: 16px; display: inline-block; letter-spacing: normal;"><span
                                                                                style="word-break: break-word; line-height: 24px;">Share
                                                                                Your Feedback</span></span></a>
                                                                    <!--[if mso]></center></v:roundrect><![endif]-->
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
                    <table class="row row-9" align="center" width="100%" border="0" cellpadding="0"
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
                    <table class="row row-10" align="center" width="100%" border="0" cellpadding="0"
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
                                                                <div class="alignment" align="center"
                                                                    style="line-height:10px">
                                                                    <div style="max-width: 150px;"><img
                                                                            src="{{ $logoUrl }}"
                                                                            style="display: block; height: auto; border: 0; width: 100%;"
                                                                            width="150" alt="Logo"
                                                                            title="Logo" height="auto"></div>
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
                                                                style="padding-bottom:10px;padding-left:40px;padding-right:40px;padding-top:20px;">
                                                                <div
                                                                    style="color:#000000;direction:ltr;font-family:'Poppins', sans-serif;font-size:14px;font-weight:400;letter-spacing:0px;line-height:1.5;text-align:center;mso-line-height-alt:21px;">
                                                                    <p style="margin: 0;">Thank you for choosing
                                                                        {{ config('app.name') }}.</p>
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
