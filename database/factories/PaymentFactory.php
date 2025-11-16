<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente a la factory.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define el estado predeterminado del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'amount' => fake()->randomFloat(2, 10, 1000),
            'status' => Payment::STATUS_PENDING,
            'transaction_id' => null,
            'response_data' => null,
            'error_message' => null,
        ];
    }

    /**
     * Indicar que el pago fue exitoso.
     */
    public function successful(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Payment::STATUS_SUCCESS,
            'transaction_id' => fake()->uuid(),
            'response_data' => [
                'success' => true,
                'message' => 'Payment processed successfully',
            ],
        ]);
    }

    /**
     * Indicar que el pago falló.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Payment::STATUS_FAILED,
            'error_message' => fake()->sentence(),
            'response_data' => [
                'success' => false,
                'error' => 'Payment processing failed',
            ],
        ]);
    }
}
