<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * El nombre del modelo correspondiente a la factory.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define el estado predeterminado del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_name' => fake()->name(),
            'total_amount' => fake()->randomFloat(2, 10, 1000),
            'status' => Order::STATUS_PENDING,
        ];
    }

    /**
     * Indicar que el pedido está pagado.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_PAID,
        ]);
    }

    /**
     * Indicar que el pedido ha fallado.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Order::STATUS_FAILED,
        ]);
    }
}
