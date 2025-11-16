<?php

return [

    /*
    |--------------------------------------------------------------------------
    | External Payment API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the external payment API service.
    | This is used to process payments through an external gateway.
    |
    */

    'payment_api' => [
        'url' => env('PAYMENT_API_URL', 'https://reqres.in/api'),
        'timeout' => env('PAYMENT_API_TIMEOUT', 30),
        'retry_attempts' => env('PAYMENT_API_RETRY_ATTEMPTS', 3),
        'success_endpoint' => env('PAYMENT_API_SUCCESS_ENDPOINT', '/users'),
    ],

];
