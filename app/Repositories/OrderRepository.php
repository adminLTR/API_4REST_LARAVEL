<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository
{
    /**
     * Crear un nuevo pedido.
     */
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    /**
     * Encontrar un pedido por ID.
     */
    public function find(int $id): ?Order
    {
        return Order::find($id);
    }

    /**
     * Encontrar un pedido por ID con sus relaciones.
     */
    public function findWithPayments(int $id): ?Order
    {
        return Order::with('payments')->find($id);
    }

    /**
     * Obtener todos los pedidos con sus pagos.
     */
    public function getAllWithPayments(): Collection
    {
        return Order::with('payments')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Actualizar el estado de un pedido.
     */
    public function updateStatus(Order $order, string $status): bool
    {
        return $order->update(['status' => $status]);
    }
}
