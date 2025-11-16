<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Orders & Payments API',
        'version' => '1.0.0',
        'endpoints' => [
            'orders' => '/api/v1/orders',
            'payments' => '/api/v1/orders/{orderId}/payments',
        ],
        'documentation' => 'See README.md for full documentation',
    ]);
});
