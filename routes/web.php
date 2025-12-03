<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'laravel_version' => app()->version(),
    ]);
});

Route::get('/test-order-status-email', function () {
    // Dummy order data
    $order = (object) [
        'customer_name' => 'John Doe',
        'tracking_number' => 'ORD-2024-HG001',
        'status' => 'shipped',
        'status_note' => 'Your order has been dispatched and is on its way to you. Expected delivery: 3-5 business days.',
        'status_updated_at' => now(),
        'currency_code' => 'GBP',
    ];

    // Dummy items data
    $items = [
        [
            'title' => 'Premium Cotton Boxer Briefs',
            'size' => 'L',
            'color' => 'Navy Blue',
            'qty' => 2,
            'line_total' => 29.98,
        ],
        [
            'title' => 'Sports Performance Trunks',
            'size' => 'M',
            'color' => 'Black',
            'qty' => 1,
            'line_total' => 19.99,
        ],
        [
            'title' => 'Classic Fit Boxers (3-Pack)',
            'size' => 'XL',
            'color' => 'Mixed',
            'qty' => 1,
            'line_total' => 34.99,
        ],
    ];

    return view('emails.orders.status', [
        'order' => $order,
        'items' => $items,
    ]);
});

Route::get('/test-order-feedback-email', function () {
    // Dummy order data
    $order = (object) [
        'customer_name' => 'John Doe',
        'tracking_number' => 'ORD-2024-HG001',
        'status_updated_at' => now(),
    ];

    // Dummy feedback URL
    $feedbackUrl = 'https://testicool.com/feedback/ORD-2024-HG001';

    return view('emails.orders.feedback', [
        'order' => $order,
        'feedbackUrl' => $feedbackUrl,
    ]);
});

Route::get('/test-payment-invoice-email', function () {
    // Dummy address data
    $address = (object) [
        'order_no' => 'ORD-2024-INV001',
        'customer_name' => 'Jane Smith',
        'currency_code' => 'GBP',
        'subtotal' => 124.94,
        'shipping_total' => 5.99,
        'discount_total' => 10.00,
        'tax_total' => 26.19,
        'grand_total' => 147.12,
        'shipping_address' => [
            'name' => 'Jane Smith',
            'address_line_1' => '123 Oxford Street',
            'address_line_2' => 'Apartment 4B',
            'city' => 'London',
            'postal_code' => 'W1D 1BS',
            'country' => 'United Kingdom',
        ],
    ];

    // Dummy order lines
    $orderLines = collect([
        (object) [
            'title_snapshot' => 'Premium Cotton Boxer Briefs',
            'qty' => 2,
            'price' => 14.99,
            'line_total' => 29.98,
            'tracking_number' => 'TRACK-001',
            'image_url' => 'https://api.testicool.co.uk/assets/images/product1.jpg',
            'meta' => [
                'variant' => [
                    'size' => 'L',
                    'color' => 'Navy Blue',
                ],
            ],
        ],
        (object) [
            'title_snapshot' => 'Sports Performance Trunks',
            'qty' => 3,
            'price' => 19.99,
            'line_total' => 59.97,
            'tracking_number' => 'TRACK-002',
            'image_url' => '',
            'meta' => [
                'variant' => [
                    'size' => 'M',
                    'color' => 'Black',
                ],
            ],
        ],
        (object) [
            'title_snapshot' => 'Classic Fit Boxers (3-Pack)',
            'qty' => 1,
            'price' => 34.99,
            'line_total' => 34.99,
            'tracking_number' => 'TRACK-003',
            'image_url' => '',
            'meta' => [
                'variant' => [
                    'size' => 'XL',
                    'color' => 'Mixed',
                ],
            ],
        ],
    ]);

    // Dummy payment data
    $payment = (object) [
        'subtotal' => 124.94,
        'shipping_cost' => 5.99,
        'tax' => 26.19,
        'discount' => 10.00,
        'total' => 147.12,
        'status' => 'paid',
        'provider' => 'Stripe',
        'provider_ref' => 'pi_3QRst4uvwxyz',
        'payment_date' => now(),
    ];

    return view('emails.payment.invoice', [
        'address' => $address,
        'orderLines' => $orderLines,
        'payment' => $payment,
        'shipping' => $address->shipping_address,
    ]);
});

Route::get('/test-payment-status-email', function () {
    // Dummy address data
    $address = (object) [
        'order_no' => 'ORD-2024-PAY001',
        'customer_name' => 'Sarah Johnson',
        'currency_code' => 'GBP',
        'subtotal' => 89.97,
        'shipping_total' => 4.99,
        'discount_total' => 5.00,
        'tax_total' => 17.99,
        'grand_total' => 107.95,
        'shipping_address' => [
            'firstName' => 'Sarah',
            'lastName' => 'Johnson',
            'address1' => '456 High Street',
            'address2' => 'Flat 2',
            'city' => 'Manchester',
            'state' => 'Greater Manchester',
            'postal' => 'M1 2AB',
            'country' => 'United Kingdom',
            'phone' => '+44 7700 900123',
        ],
    ];

    // Dummy order lines
    $orderLines = collect([
        (object) [
            'title_snapshot' => 'Premium Cotton Boxer Briefs',
            'qty' => 2,
            'price' => 14.99,
            'line_total' => 29.98,
            'tracking_number' => 'TRACK-PAY-001',
            'image_url' => 'https://api.testicool.co.uk/assets/images/product1.jpg',
            'meta' => [
                'variant' => [
                    'size' => 'M',
                    'color' => 'Black',
                ],
            ],
        ],
        (object) [
            'title_snapshot' => 'Sports Performance Trunks',
            'qty' => 2,
            'price' => 19.99,
            'line_total' => 39.98,
            'tracking_number' => 'TRACK-PAY-002',
            'image_url' => '',
            'meta' => [
                'variant' => [
                    'size' => 'L',
                    'color' => 'Navy',
                ],
            ],
        ],
        (object) [
            'title_snapshot' => 'Classic Comfort Boxers',
            'qty' => 1,
            'price' => 19.99,
            'line_total' => 19.99,
            'tracking_number' => 'TRACK-PAY-003',
            'image_url' => '',
            'meta' => [
                'variant' => [
                    'size' => 'XL',
                    'color' => 'Grey',
                ],
            ],
        ],
    ]);

    // Dummy payment data
    $payment = (object) [
        'provider' => 'Stripe',
        'provider_ref' => 'pi_3QRst5xyz789',
    ];

    // Payment status: 'succeeded', 'failed', or 'pending'
    $status = 'succeeded';
    $reason = null; // or 'Insufficient funds' for failed payments

    return view('emails.payment.status', [
        'address' => $address,
        'orderLines' => $orderLines,
        'payment' => $payment,
        'status' => $status,
        'reason' => $reason,
    ]);
});
