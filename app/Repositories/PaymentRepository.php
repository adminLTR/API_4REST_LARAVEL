<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Models\Order;

class PaymentRepository
{
    /**
     * Crear un nuevo pago.
     */
    public function create(array $data): Payment
    {
        return Payment::create($data);
    }

    /**
     * Encontrar un pago por ID.
     */
    public function find(int $id): ?Payment
    {
        return Payment::find($id);
    }

    /**
     * Obtener todos los pagos de un pedido.
     */
    public function getByOrder(Order $order)
    {
        return $order->payments()->orderBy('created_at', 'desc')->get();
    }

    /**
     * Actualizar un pago.
     */
    public function update(Payment $payment, array $data): bool
    {
        return $payment->update($data);
    }
}
