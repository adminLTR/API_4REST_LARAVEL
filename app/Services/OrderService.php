<?php

namespace App\Services;

use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Collection;

class OrderService
{
    public function __construct(
        private OrderRepository $orderRepository
    ) {}

    /**
     * Crear un nuevo pedido.
     */
    public function createOrder(string $customerName, float $totalAmount): Order
    {
        return $this->orderRepository->create([
            'customer_name' => $customerName,
            'total_amount' => $totalAmount,
            'status' => Order::STATUS_PENDING,
        ]);
    }

    /**
     * Obtener un pedido por ID.
     */
    public function getOrder(int $id): ?Order
    {
        return $this->orderRepository->findWithPayments($id);
    }

    /**
     * Obtener todos los pedidos con sus pagos.
     */
    public function getAllOrders(): Collection
    {
        return $this->orderRepository->getAllWithPayments();
    }

    /**
     * Verificar si un pedido puede recibir un nuevo pago.
     */
    public function canReceivePayment(Order $order): bool
    {
        return $order->canReceivePayment();
    }

    /**
     * Marcar pedido como pagado.
     */
    public function markAsPaid(Order $order): void
    {
        $this->orderRepository->updateStatus($order, Order::STATUS_PAID);
    }

    /**
     * Marcar pedido como fallido.
     */
    public function markAsFailed(Order $order): void
    {
        $this->orderRepository->updateStatus($order, Order::STATUS_FAILED);
    }
}
