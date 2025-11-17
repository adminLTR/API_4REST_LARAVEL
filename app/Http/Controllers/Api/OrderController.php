<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    /**
     * @OA\Get(
     *     path="/orders",
     *     operationId="getOrdersList",
     *     tags={"Orders"},
     *     summary="Listar todos los pedidos",
     *     description="Retorna una lista de todos los pedidos con sus pagos asociados y el número de intentos de pago realizados",
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pedidos obtenida exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Order")
     *             )
     *         )
     *     )
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        $orders = $this->orderService->getAllOrders();
        
        return OrderResource::collection($orders);
    }

    /**
     * @OA\Post(
     *     path="/orders",
     *     operationId="createOrder",
     *     tags={"Orders"},
     *     summary="Crear un nuevo pedido",
     *     description="Crea un nuevo pedido con estado inicial 'pending'. El pedido queda listo para recibir intentos de pago.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del pedido a crear",
     *         @OA\JsonContent(
     *             required={"customer_name", "total_amount"},
     *             @OA\Property(
     *                 property="customer_name",
     *                 type="string",
     *                 example="Juan Pérez",
     *                 description="Nombre completo del cliente (mínimo 3 caracteres)"
     *             ),
     *             @OA\Property(
     *                 property="total_amount",
     *                 type="number",
     *                 format="float",
     *                 example=150.50,
     *                 description="Monto total del pedido (debe ser mayor a 0 y menor a 1000000)"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pedido creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Order created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Order")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The customer name field is required."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="customer_name",
     *                     type="array",
     *                     @OA\Items(type="string", example="The customer name field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function store(CreateOrderRequest $request): JsonResponse
    {
        $order = $this->orderService->createOrder(
            $request->validated('customer_name'),
            $request->validated('total_amount')
        );

        return response()->json([
            'message' => 'Order created successfully',
            'data' => new OrderResource($order),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/orders/{id}",
     *     operationId="getOrderById",
     *     tags={"Orders"},
     *     summary="Obtener un pedido específico",
     *     description="Retorna los detalles completos de un pedido incluyendo todos sus pagos asociados",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del pedido",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pedido encontrado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Order")
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
    public function show(int $id): JsonResponse
    {
        $order = $this->orderService->getOrder($id);

        if (!$order) {
            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        return response()->json([
            'data' => new OrderResource($order),
        ]);
    }
}
