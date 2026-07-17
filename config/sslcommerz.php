<?php

declare(strict_types=1);

// config for Raziul/Sslcommerz

return [
    /**
     * Enable/Disable Sandbox mode.
     * true  = Sandbox (development/testing)
     * false = Live (PRODUCTION) — SWAP THIS BEFORE GOING LIVE.
     */
    'sandbox' => env('SSLCOMMERZ_TESTMODE', true),

    /**
     * The API credentials given from SSLCommerz.
     * Never hardcode these — they are read from .env only.
     * SWAP sandbox credentials for LIVE credentials before production.
     */
    'store' => [
        'id' => env('SSLCOMMERZ_STORE_ID'),
        'password' => env('SSLCOMMERZ_STORE_PASSWORD'),
        'currency' => env('SSLCOMMERZ_STORE_CURRENCY', 'BDT'),
    ],

    /**
     * Route names for success/failure/cancel/ipn.
     * These map to the payment.* routes defined in routes/web.php.
     */
    'route' => [
        'success' => env('SSLCOMMERZ_ROUTE_SUCCESS', 'payment.success'),
        'failure' => env('SSLCOMMERZ_ROUTE_FAILURE', 'payment.failed'),
        'cancel' => env('SSLCOMMERZ_ROUTE_CANCEL', 'payment.cancelled'),
        'ipn' => env('SSLCOMMERZ_ROUTE_IPN', 'payment.ipn'),
    ],

    /**
     * Product profile required from SSLC
     * By default it is "general"
     *
     * AVAILABLE PROFILES
     *  general
     *  physical-goods
     *  non-physical-goods
     *  airline-tickets
     *  travel-vertical
     *  telecom-vertical
     */
    'product_profile' => 'general',
];
