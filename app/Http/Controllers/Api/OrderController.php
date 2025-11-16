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
     * Listar todos los pedidos con sus pagos.
     */
    public function index(): AnonymousResourceCollection
    {
        $orders = $this->orderService->getAllOrders();
        
        return OrderResource::collection($orders);
    }

    /**
     * Crear un nuevo pedido.
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
     * Mostrar un pedido específico con sus pagos.
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
