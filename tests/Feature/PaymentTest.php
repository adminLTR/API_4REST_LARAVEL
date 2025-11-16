<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Payment;
use App\Services\ExternalPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que se puede procesar un pago exitosamente.
     */
    public function test_can_process_payment_successfully(): void
    {
        $order = Order::factory()->create([
            'total_amount' => 100.00,
            'status' => Order::STATUS_PENDING,
        ]);

        // Mock del servicio de pagos externo
        $this->mock(ExternalPaymentService::class, function ($mock) {
            $mock->shouldReceive('processPayment')
                ->once()
                ->andReturn([
                    'success' => true,
                    'transaction_id' => 'txn_123456',
                    'message' => 'Payment processed successfully',
                    'data' => ['id' => 1, 'status' => 'completed'],
                ]);
        });

        $response = $this->postJson("/api/v1/orders/{$order->id}/payments");

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Payment processed successfully',
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'amount',
                    'status',
                    'transaction_id',
                    'created_at',
                    'updated_at',
                ],
            ]);

        // Verificar que el pago fue creado
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'amount' => 100.00,
            'status' => Payment::STATUS_SUCCESS,
            'transaction_id' => 'txn_123456',
        ]);

        // Verificar que el pedido cambió a estado "paid"
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_PAID,
        ]);
    }

    /**
     * Test que un pago fallido marca el pedido como fallido.
     */
    public function test_failed_payment_marks_order_as_failed(): void
    {
        $order = Order::factory()->create([
            'total_amount' => 100.00,
            'status' => Order::STATUS_PENDING,
        ]);

        // Mock del servicio de pagos externo con fallo
        $this->mock(ExternalPaymentService::class, function ($mock) {
            $mock->shouldReceive('processPayment')
                ->once()
                ->andReturn([
                    'success' => false,
                    'error' => 'Payment gateway rejected the transaction',
                    'message' => 'Insufficient funds',
                    'data' => null,
                ]);
        });

        $response = $this->postJson("/api/v1/orders/{$order->id}/payments");

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);

        // Verificar que el pago fue creado con estado fallido
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => Payment::STATUS_FAILED,
        ]);

        // Verificar que el pedido cambió a estado "failed"
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_FAILED,
        ]);
    }

    /**
     * Test que un pedido fallido puede recibir nuevos intentos de pago.
     */
    public function test_failed_order_can_receive_new_payment_attempts(): void
    {
        $order = Order::factory()->create([
            'total_amount' => 100.00,
            'status' => Order::STATUS_FAILED,
        ]);

        // Crear un pago fallido previo
        Payment::factory()->failed()->create([
            'order_id' => $order->id,
            'amount' => 100.00,
        ]);

        // Mock del servicio de pagos externo con éxito
        $this->mock(ExternalPaymentService::class, function ($mock) {
            $mock->shouldReceive('processPayment')
                ->once()
                ->andReturn([
                    'success' => true,
                    'transaction_id' => 'txn_789012',
                    'message' => 'Payment processed successfully',
                    'data' => ['id' => 2, 'status' => 'completed'],
                ]);
        });

        $response = $this->postJson("/api/v1/orders/{$order->id}/payments");

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ]);

        // Verificar que se creó un segundo pago
        $this->assertEquals(2, Payment::where('order_id', $order->id)->count());

        // Verificar que el pedido ahora está pagado
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => Order::STATUS_PAID,
        ]);
    }

    /**
     * Test que un pedido ya pagado no puede recibir nuevos pagos.
     */
    public function test_paid_order_cannot_receive_new_payments(): void
    {
        $order = Order::factory()->paid()->create([
            'total_amount' => 100.00,
        ]);

        Payment::factory()->successful()->create([
            'order_id' => $order->id,
            'amount' => 100.00,
        ]);

        $response = $this->postJson("/api/v1/orders/{$order->id}/payments");

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Order cannot receive payments. Current status: paid',
            ]);
    }

    /**
     * Test que se pueden listar todos los pagos de un pedido.
     */
    public function test_can_list_payments_for_order(): void
    {
        $order = Order::factory()->create();

        Payment::factory()->count(3)->create([
            'order_id' => $order->id,
        ]);

        $response = $this->getJson("/api/v1/orders/{$order->id}/payments");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'amount',
                        'status',
                        'transaction_id',
                        'error_message',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    /**
     * Test que retorna 404 al intentar crear pago para pedido inexistente.
     */
    public function test_returns_404_when_creating_payment_for_nonexistent_order(): void
    {
        $response = $this->postJson('/api/v1/orders/999/payments');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Order not found',
            ]);
    }

    /**
     * Test el flujo completo: crear pedido, intentar pago que falla, reintentar con éxito.
     */
    public function test_complete_payment_flow_with_retry(): void
    {
        // Crear pedido
        $orderData = [
            'customer_name' => 'Carlos López',
            'total_amount' => 250.00,
        ];

        $orderResponse = $this->postJson('/api/v1/orders', $orderData);
        $orderResponse->assertStatus(201);

        $orderId = $orderResponse->json('data.id');

        // Primer intento de pago (falla)
        $this->mock(ExternalPaymentService::class, function ($mock) {
            $mock->shouldReceive('processPayment')
                ->once()
                ->andReturn([
                    'success' => false,
                    'error' => 'Card declined',
                    'message' => 'Payment failed',
                    'data' => null,
                ]);
        });

        $paymentResponse1 = $this->postJson("/api/v1/orders/{$orderId}/payments");
        $paymentResponse1->assertStatus(422)
            ->assertJson(['success' => false]);

        // Verificar que el pedido está fallido
        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'status' => Order::STATUS_FAILED,
        ]);

        // Segundo intento de pago (éxito)
        $this->mock(ExternalPaymentService::class, function ($mock) {
            $mock->shouldReceive('processPayment')
                ->once()
                ->andReturn([
                    'success' => true,
                    'transaction_id' => 'txn_success_123',
                    'message' => 'Payment processed successfully',
                    'data' => ['id' => 3, 'status' => 'completed'],
                ]);
        });

        $paymentResponse2 = $this->postJson("/api/v1/orders/{$orderId}/payments");
        $paymentResponse2->assertStatus(201)
            ->assertJson(['success' => true]);

        // Verificar que el pedido ahora está pagado
        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'status' => Order::STATUS_PAID,
        ]);

        // Verificar que hay 2 intentos de pago
        $this->assertEquals(2, Payment::where('order_id', $orderId)->count());
    }
}
