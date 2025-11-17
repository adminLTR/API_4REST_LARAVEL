<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Orders & Payments API",
 *     description="API REST para gestionar Pedidos y Pagos con integración a API externa simulada. Esta API permite crear pedidos, procesarlos mediante pagos externos y gestionar reintentos en caso de fallos.",
 *     @OA\Contact(
 *         email="support@ordersapi.com",
 *         name="API Support"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000/api/v1",
 *     description="Servidor de desarrollo local"
 * )
 * 
 * @OA\Tag(
 *     name="Orders",
 *     description="Endpoints para gestionar pedidos (Orders). Permite crear, listar y consultar pedidos con su estado actual."
 * )
 * 
 * @OA\Tag(
 *     name="Payments",
 *     description="Endpoints para procesar pagos (Payments). Permite procesar pagos a través de API externa y consultar historial de intentos de pago."
 * )
 * 
 * @OA\Schema(
 *     schema="Order",
 *     type="object",
 *     title="Order",
 *     description="Modelo de pedido",
 *     @OA\Property(property="id", type="integer", example=1, description="ID único del pedido"),
 *     @OA\Property(property="customer_name", type="string", example="Juan Pérez", description="Nombre del cliente"),
 *     @OA\Property(property="total_amount", type="number", format="float", example=150.50, description="Monto total del pedido"),
 *     @OA\Property(property="status", type="string", enum={"pending", "paid", "failed"}, example="pending", description="Estado actual del pedido"),
 *     @OA\Property(property="payment_attempts", type="integer", example=0, description="Número de intentos de pago realizados"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(
 *         property="payments", 
 *         type="array",
 *         description="Lista de pagos asociados al pedido",
 *         @OA\Items(ref="#/components/schemas/Payment")
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="Payment",
 *     type="object",
 *     title="Payment",
 *     description="Modelo de pago",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="amount", type="number", format="float", example=150.50),
 *     @OA\Property(property="status", type="string", enum={"pending", "success", "failed"}, example="success"),
 *     @OA\Property(property="transaction_id", type="string", nullable=true, example="txn_abc123"),
 *     @OA\Property(property="error_message", type="string", nullable=true, example=null),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
abstract class Controller
{
    //
}
