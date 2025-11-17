<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ExternalPaymentService
{
    /**
     * URL base de la API de pagos externa.
     */
    private string $apiUrl;

    /**
     * Timeout para las peticiones HTTP.
     */
    private int $timeout;

    /**
     * Número de intentos de reintento.
     */
    private int $retryAttempts;

    public function __construct()
    {
        $this->apiUrl = config('services.payment_api.url');
        $this->timeout = config('services.payment_api.timeout', 30);
        $this->retryAttempts = config('services.payment_api.retry_attempts', 3);
    }

    /**
     * Procesar un pago a través de la API externa.
     * 
     * @param float $amount Monto del pago
     * @param array $additionalData Datos adicionales del pago
     * @return array
     * @throws Exception
     */
    public function processPayment(float $amount, array $additionalData = []): array
    {
        try {
            Log::info('Procesando pago externo', [
                'amount' => $amount,
                'additional_data' => $additionalData,
            ]);

            // Simular llamada a API externa usando reqres.in
            // Usamos GET /users/2 que funciona sin autenticación
            // En producción, esto sería la API real de pagos (POST)
            $response = Http::timeout($this->timeout)
                ->retry($this->retryAttempts, 100, throw: false)
                ->get($this->apiUrl . '/users/2');

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Pago procesado exitosamente', ['response' => $data]);

                // Simular fallo aleatorio para testing (20% de probabilidad)
                if (rand(1, 100) <= 20) {
                    Log::warning('Simulación de fallo de pago');
                    
                    return [
                        'success' => false,
                        'error' => 'Payment gateway rejected the transaction',
                        'message' => 'Insufficient funds or card declined',
                        'data' => null,
                    ];
                }

                return [
                    'success' => true,
                    'transaction_id' => 'txn_' . uniqid() . '_' . ($data['data']['id'] ?? rand(1000, 9999)),
                    'message' => 'Payment processed successfully',
                    'data' => [
                        'id' => $data['data']['id'] ?? rand(1, 100),
                        'status' => 'completed',
                        'amount' => $amount,
                        'currency' => 'USD',
                    ],
                ];
            }

            Log::error('Error al procesar pago', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => 'Payment processing failed',
                'message' => 'Payment gateway error: ' . $response->status(),
                'data' => null,
            ];

        } catch (Exception $e) {
            Log::error('Excepción al procesar pago', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'error' => 'Payment service unavailable',
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Verificar el estado de una transacción.
     */
    public function checkTransactionStatus(string $transactionId): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->apiUrl . '/users/' . $transactionId);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'status' => 'completed',
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => false,
                'status' => 'unknown',
                'data' => null,
            ];

        } catch (Exception $e) {
            Log::error('Error al verificar estado de transacción', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'status' => 'error',
                'data' => null,
            ];
        }
    }
}
