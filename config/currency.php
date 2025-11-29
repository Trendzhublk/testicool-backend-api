<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Base Currency
    |--------------------------------------------------------------------------
    |
    | All prices are stored in the base currency (GBP). Conversions to other
    | currencies are done on the fly using the provider configured below.
    |
    */
    'base' => env('CURRENCY_BASE', 'GBP'),

    /*
    |--------------------------------------------------------------------------
    | Supported Currencies
    |--------------------------------------------------------------------------
    |
    | List of currencies that can be returned to the client. Symbols are used
    | for presentation only.
    |
    */
    'available' => [
        'GBP' => ['symbol' => '£'],
        'USD' => ['symbol' => '$'],
        'EUR' => ['symbol' => '€'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Provider
    |--------------------------------------------------------------------------
    |
    | A free, no-auth API endpoint is used to fetch live rates. Cache TTL is
    | in minutes.
    |
    */
    'provider' => [
        'url' => env('CURRENCY_PROVIDER_URL', 'https://api.exchangerate.host/latest'),
        'cache_ttl' => (int) env('CURRENCY_RATE_CACHE_MINUTES', 360),
    ],
];
