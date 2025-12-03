<?php

use App\Http\Controllers\Api\v1\CheckoutController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v1\CurrencyController;
use App\Http\Controllers\Api\v1\StripeWebhookController;
use App\Http\Controllers\Api\v1\CountryController;
use App\Http\Controllers\Api\v1\OrderTrackingController;
use App\Http\Controllers\Api\v1\PaymentMethodController;
use App\Http\Controllers\Api\v1\FeedbackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product:slug}', [ProductController::class, 'show']);

    Route::get('/currencies', [CurrencyController::class, 'index']);
    Route::get('/countries', [CountryController::class, 'index']);

    Route::prefix('checkout')->group(function () {
        Route::post('/discounts/validate', [CheckoutController::class, 'validateDiscount']);
        Route::get('/shipping-rates', [CheckoutController::class, 'shippingRates']);
        Route::get('/agents', [CheckoutController::class, 'shippingAgents']);
        Route::get('/payment-methods', [PaymentMethodController::class, 'index']);
        Route::post('/session', [CheckoutController::class, 'createStripeSession']);
        Route::get('/session', [CheckoutController::class, 'getStripeSession']);
    });

    Route::post('/stripe/webhook', StripeWebhookController::class);
    Route::post('/feedbacks', [FeedbackController::class, 'store']);

    Route::get('/orders/track/{tracking}', [OrderTrackingController::class, 'track']);
    Route::post('/orders/{order}/status', [OrderTrackingController::class, 'updateStatus']);
});
