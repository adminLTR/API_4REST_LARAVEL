<?php

namespace Tests\Feature;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que se puede crear un pedido exitosamente.
     */
    public function test_can_create_order_successfully(): void
    {
        $orderData = [
            'customer_name' => 'Juan Pérez',
            'total_amount' => 150.50,
        ];

        $response = $this->postJson('/api/v1/orders', $orderData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'customer_name',
                    'total_amount',
                    'status',
                    'payment_attempts',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJson([
                'data' => [
                    'customer_name' => 'Juan Pérez',
                    'total_amount' => '150.50',
                    'status' => Order::STATUS_PENDING,
                    'payment_attempts' => 0,
                ],
            ]);

        $this->assertDatabaseHas('orders', [
            'customer_name' => 'Juan Pérez',
            'total_amount' => 150.50,
            'status' => Order::STATUS_PENDING,
        ]);
    }

    /**
     * Test validación de campos requeridos al crear pedido.
     */
    public function test_create_order_requires_validation(): void
    {
        $response = $this->postJson('/api/v1/orders', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['customer_name', 'total_amount']);
    }

    /**
     * Test que se puede listar todos los pedidos.
     */
    public function test_can_list_all_orders(): void
    {
        Order::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/orders');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'customer_name',
                        'total_amount',
                        'status',
                        'payment_attempts',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    /**
     * Test que se puede obtener un pedido específico.
     */
    public function test_can_get_specific_order(): void
    {
        $order = Order::factory()->create([
            'customer_name' => 'María García',
            'total_amount' => 200.00,
        ]);

        $response = $this->getJson("/api/v1/orders/{$order->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $order->id,
                    'customer_name' => 'María García',
                    'total_amount' => '200.00',
                    'status' => Order::STATUS_PENDING,
                ],
            ]);
    }

    /**
     * Test que retorna 404 cuando el pedido no existe.
     */
    public function test_returns_404_when_order_not_found(): void
    {
        $response = $this->getJson('/api/v1/orders/999');

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'Order not found',
            ]);
    }

    /**
     * Test validación de monto mínimo.
     */
    public function test_order_amount_must_be_positive(): void
    {
        $orderData = [
            'customer_name' => 'Test User',
            'total_amount' => 0,
        ];

        $response = $this->postJson('/api/v1/orders', $orderData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['total_amount']);
    }

    /**
     * Test validación de límite máximo del monto.
     */
    public function test_order_amount_has_maximum_limit(): void
    {
        $orderData = [
            'customer_name' => 'Test User',
            'total_amount' => 10000000.00,
        ];

        $response = $this->postJson('/api/v1/orders', $orderData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['total_amount']);
    }
}
