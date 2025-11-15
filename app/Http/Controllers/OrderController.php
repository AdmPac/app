<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $service)
    {
    }
    
    public function getByID($uid): JsonResponse
    {
        $orderData = $this->service->getByID($uid);
        return response()->json(array_values($orderData));
    }

    public function get(): JsonResponse
    {
        $orderData = $this->service->get();
        return response()->json(array_values($orderData));
    }

    public function patch(Request $request, $id): JsonResponse
    {
        try {
            $update = $this->service->patch($request, $id);
            if ($update) {
                return response()->json([
                    'message' => 'Заказ успешно обновлен',
                ]);
            }
            return response()->json([
                'message' => 'Ошибка обновления',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
