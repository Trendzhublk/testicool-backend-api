@php
    $customer = $order->customer_name ?? 'there';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root { color-scheme: light dark; }
        body { margin:0; padding:0; background:#f1f5f9; font-family:'Helvetica Neue', Arial, sans-serif; color:#0b1221; }
        .wrapper { width:100%; padding:26px 0; }
        .container { max-width:680px; margin:0 auto; background:#0b1221; border-radius:18px; overflow:hidden; box-shadow:0 18px 60px rgba(0,0,0,0.35); }
        .header { padding:28px; background:linear-gradient(180deg, #0b132b, #111827 80%); color:#f8fafc; }
        .brand { font-size:20px; font-weight:800; letter-spacing:0.4px; }
        .headline { margin:12px 0 4px; font-size:24px; font-weight:700; }
        .content { padding:26px 28px 30px; background:#0b1221; color:#e2e8f0; }
        .panel { margin-top:12px; padding:14px; border-radius:12px; background:#0f172a; border:1px solid #1f2937; }
        .btn { display:inline-block; margin-top:20px; padding:14px 22px; background:#f8fafc; color:#0b1221 !important; border-radius:14px; font-weight:700; text-decoration:none; border:1px solid #cbd5e1; box-shadow:0 8px 22px rgba(15,23,42,0.28); }
        .footer { text-align:center; padding:22px; color:#cbd5e1; font-size:12px; }
        @media (prefers-color-scheme: dark) {
            body { background:#030712; color:#e2e8f0; }
            .container { background:#0b1221; box-shadow:none; }
            .btn { background:#f8fafc; color:#0b1221 !important; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <div class="brand">{{ config('app.name') }}</div>
                <div class="headline">Your order arrived! 🎉</div>
                <div style="margin-top:6px; opacity:0.85;">Hi {{ $customer }}, we hope you love it.</div>
            </div>
            <div class="content">
                <div>We’d love to hear how everything went with order <strong>{{ $order->tracking_number }}</strong>.</div>
                <div class="panel">
                    <div><strong>Order #:</strong> {{ $order->tracking_number }}</div>
                    <div><strong>Status:</strong> Delivered</div>
                    <div><strong>Updated:</strong> {{ optional($order->status_updated_at)->toDateTimeString() }}</div>
                </div>
                <a class="btn" href="{{ $feedbackUrl }}">Share your feedback</a>
            </div>
        </div>
        <div class="footer">
            Thank you for choosing {{ config('app.name') }}.
        </div>
    </div>
</body>
</html>
