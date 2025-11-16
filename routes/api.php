<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    // Rutas de Pedidos (Orders)
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{id}', [OrderController::class, 'show']);
        
        // Rutas de Pagos (Payments) para un pedido específico
        Route::prefix('{orderId}/payments')->group(function () {
            Route::get('/', [PaymentController::class, 'index']);
            Route::post('/', [PaymentController::class, 'store']);
        });
    });
});
