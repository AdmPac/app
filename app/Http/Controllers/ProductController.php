<?php

namespace App\Http\Controllers;


use App\Services\OrderService;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Отвечает за продукты
 */
class ProductController extends Controller
{
    public function __construct(private ProductService $service)
    {
    }
    
    /**
     * Display a listing of the resource.
     */
    public function getAll(): JsonResponse
    {
        $products = $this->service->getAll();
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function post(Request $request): JsonResponse
    {
        $products = $this->service->post($request);
        if ($products) {
            return response()->json($products);
        }
        return response()->json([
            'message' => 'Ошибка создания'
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function get(int $id)
    {
        try {
            $products = $this->service->get($id);
            return response()->json($products);
        } catch (ModelNotFoundException $e) {
            return response()->json($e->getMessage(), 404);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function patch(Request $request, int $id)
    {
        $update = $this->service->patch($request, $id);
        if ($update) {
            return response()->json(['message' => 'Продукт успешно обновлен']);
        }
        return response()->json([
            'message' => 'Ошибка обновления'
        ], 500);
    }

    /**
     * Update the specified resource in storage.
     */
    public function put(Request $request, int $id)
    {
        $update = $this->service->put($request, $id);
        if ($update) {
            return response()->json(['message' => 'Продукт успешно обновлен']);
        }
        return response()->json([
            'message' => 'Ошибка обновления'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(int $id)
    {
        $isDeleted = $this->service->delete($id);
        if ($isDeleted) {
            return response()->json([
                'message' => 'Успешное удаление',
            ]);
        }
        return response()->json([
            'message' => 'Ошибка удаления',
        ], 500);
    }
}
