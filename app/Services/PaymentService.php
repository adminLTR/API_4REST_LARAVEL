<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Repositories\PaymentRepository;
use App\Services\ExternalPaymentService;
use App\Services\OrderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentService
{
    public function __construct(
        private PaymentRepository $paymentRepository,
        private ExternalPaymentService $externalPaymentService,
        private OrderService $orderService
    ) {}

    /**
     * Procesar un pago para un pedido.
     */
    public function processPayment(Order $order): array
    {
        // Verificar si el pedido puede recibir pagos
        if (!$this->orderService->canReceivePayment($order)) {
            return [
                'success' => false,
                'message' => 'Order cannot receive payments. Current status: ' . $order->status,
                'payment' => null,
            ];
        }

        return DB::transaction(function () use ($order) {
            // Crear registro de pago con estado pendiente
            $payment = $this->paymentRepository->create([
                'order_id' => $order->id,
                'amount' => $order->total_amount,
                'status' => Payment::STATUS_PENDING,
            ]);

            try {
                // Llamar a la API externa de pagos
                $result = $this->externalPaymentService->processPayment(
                    $order->total_amount,
                    [
                        'order_id' => $order->id,
                        'customer_name' => $order->customer_name,
                    ]
                );

                if ($result['success']) {
                    // Marcar pago como exitoso
                    $this->paymentRepository->update($payment, [
                        'status' => Payment::STATUS_SUCCESS,
                        'transaction_id' => $result['transaction_id'],
                        'response_data' => $result['data'],
                    ]);

                    // Marcar pedido como pagado
                    $this->orderService->markAsPaid($order);

                    Log::info('Payment processed successfully', [
                        'order_id' => $order->id,
                        'payment_id' => $payment->id,
                        'transaction_id' => $result['transaction_id'],
                    ]);

                    return [
                        'success' => true,
                        'message' => 'Payment processed successfully',
                        'payment' => $payment->fresh(),
                        'transaction_id' => $result['transaction_id'],
                    ];
                } else {
                    // Marcar pago como fallido
                    $this->paymentRepository->update($payment, [
                        'status' => Payment::STATUS_FAILED,
                        'error_message' => $result['error'] ?? 'Payment failed',
                        'response_data' => $result['data'],
                    ]);

                    // Marcar pedido como fallido
                    $this->orderService->markAsFailed($order);

                    Log::warning('Payment failed', [
                        'order_id' => $order->id,
                        'payment_id' => $payment->id,
                        'error' => $result['error'],
                    ]);

                    return [
                        'success' => false,
                        'message' => $result['message'] ?? 'Payment processing failed',
                        'payment' => $payment->fresh(),
                        'error' => $result['error'],
                    ];
                }
            } catch (Exception $e) {
                // Marcar pago como fallido en caso de excepción
                $this->paymentRepository->update($payment, [
                    'status' => Payment::STATUS_FAILED,
                    'error_message' => $e->getMessage(),
                ]);

                // Marcar pedido como fallido
                $this->orderService->markAsFailed($order);

                Log::error('Exception processing payment', [
                    'order_id' => $order->id,
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return [
                    'success' => false,
                    'message' => 'An error occurred processing the payment',
                    'payment' => $payment->fresh(),
                    'error' => $e->getMessage(),
                ];
            }
        });
    }

    /**
     * Obtener todos los pagos de un pedido.
     */
    public function getPaymentsByOrder(Order $order)
    {
        return $this->paymentRepository->getByOrder($order);
    }
}
