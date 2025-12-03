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
        // Default is a free, no-auth API that returns all rates for the base currency.
        // Use {base} placeholder to inject the base currency code.
        'url' => env('CURRENCY_PROVIDER_URL', 'https://open.er-api.com/v6/latest/{base}'),
        'cache_ttl' => (int) env('CURRENCY_RATE_CACHE_MINUTES', 360),
    ],
];
