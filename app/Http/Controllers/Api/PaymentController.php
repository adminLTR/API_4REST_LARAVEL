<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService,
        private OrderService $orderService
    ) {}

    /**
     * @OA\Post(
     *     path="/orders/{orderId}/payments",
     *     operationId="processPayment",
     *     tags={"Payments"},
     *     summary="Procesar un pago para un pedido",
     *     description="Procesa un pago por el monto total del pedido a través de la API externa. Si el pago es exitoso, el pedido pasa a estado 'paid'. Si falla, el pedido pasa a 'failed' y puede recibir nuevos intentos. Los pedidos en estado 'paid' no pueden recibir más pagos.",
     *     @OA\Parameter(
     *         name="orderId",
     *         in="path",
     *         description="ID del pedido al que se le procesará el pago",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pago procesado exitosamente - El pedido ahora está en estado 'paid'",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Payment processed successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Payment")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pedido no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Order not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="No se puede procesar el pago. El pedido ya está pagado o hubo un error en el procesamiento.",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Order is already paid and cannot receive new payments"),
     *             @OA\Property(property="data", ref="#/components/schemas/Payment", nullable=true)
     *         )
     *     )
     * )
     */
    public function store(int $orderId): JsonResponse
    {
        $order = $this->orderService->getOrder($orderId);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        $result = $this->paymentService->processPayment($order);

        $statusCode = $result['success'] ? 201 : 422;

        $response = [
            'success' => $result['success'],
            'message' => $result['message'],
        ];

        if (isset($result['payment']) && $result['payment']) {
            $response['data'] = new PaymentResource($result['payment']);
        }

        return response()->json($response, $statusCode);
    }

    /**
     * @OA\Get(
     *     path="/orders/{orderId}/payments",
     *     operationId="getOrderPayments",
     *     tags={"Payments"},
     *     summary="Listar todos los pagos de un pedido",
     *     description="Retorna una lista completa de todos los intentos de pago realizados para un pedido específico, incluyendo pagos exitosos y fallidos",
     *     @OA\Parameter(
     *         name="orderId",
     *         in="path",
     *         description="ID del pedido del cual se listarán los pagos",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pagos obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Payment")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pedido no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Order not found")
     *         )
     *     )
     * )
     */
    public function index(int $orderId): JsonResponse
    {
        $order = $this->orderService->getOrder($orderId);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        $payments = $this->paymentService->getPaymentsByOrder($order);

        return response()->json([
            'data' => PaymentResource::collection($payments),
        ]);
    }
}
