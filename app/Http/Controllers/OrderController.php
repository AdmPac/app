<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusPatchRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $service)
    {
    }
    
    public function getByUserID(int $uid): JsonResponse
    {
        $orderData = $this->service->getByUserID($uid);
        return response()->json($orderData);
    }

    public function get(): JsonResponse
    {
        $orderData = $this->service->get();
        return response()->json($orderData);
    }

    public function patch(StatusPatchRequest $request, int $id): JsonResponse
    {
        $update = $this->service->patch($request->validated(), $id);
        if ($update) {
            return response()->json([
                'message' => 'Заказ успешно обновлен',
            ]);
        }
        return response()->json([
            'message' => 'Ошибка обновления',
        ], 500);
    }
}
