<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private OrderService $orderService
    ) {}

    /**
     * Procesar un pago para un pedido.
     */
    public function store(int $orderId): JsonResponse
    {
        $order = $this->orderService->getOrder($orderId);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        $result = $this->paymentService->processPayment($order);

        $statusCode = $result['success'] ? 201 : 422;

        return response()->json([
            'success' => $result['success'],
            'message' => $result['message'],
            'data' => new PaymentResource($result['payment']),
        ], $statusCode);
    }

    /**
     * Listar todos los pagos de un pedido.
     */
    public function index(int $orderId): JsonResponse
    {
        $order = $this->orderService->getOrder($orderId);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        $payments = $this->paymentService->getPaymentsByOrder($order);

        return response()->json([
            'data' => PaymentResource::collection($payments),
        ]);
    }
}
