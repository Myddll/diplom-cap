<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Services\Order\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {
    }

    public function newOrder(CreateOrderRequest $request): JsonResponse
    {
        $data = $request->validated();

        $this->orderService->addOrder($data);

        return response()->json([
            'status' => true,
        ]);
    }
}
