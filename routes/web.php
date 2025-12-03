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
