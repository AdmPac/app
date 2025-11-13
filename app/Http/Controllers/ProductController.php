<?php

namespace App\Http\Controllers;

use App\Models\Order\Status;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product\Status as ProductStatus;

/**
 * Отвечает за продукты
 */
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getAll()
    {
        $products = Product::whereHas('status', function ($q) {
            $q->where('code', 1);
        })->with('status')->get();
        
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function post(Request $request)
    {
        $request->validate([
            'img' => 'required|string',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cost' => 'required|numeric',
            'type_id' => 'required|integer',
            'status_id' => 'required|integer',
            'limit' => 'required|integer',
        ]);

        $product = Product::create($request->all());
        if ($product) {
            return response()->json($product);
        }
        return response()->json([
            'message' => 'Ошибка создания'
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function get(string $id)
    {
        $product = Product::where('id', $id)
            ->whereHas('status', function ($q) {
                $q->where('code', 1);
            })
            ->firstOrFail();
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function patch(Request $request, string $id)
    {
        $request->validate([
            'img' => 'sometimes|string',
            'name' => 'sometimes|nullable|string|max:255',
            'description' => 'sometimes|nullable|nullable|string',
            'cost' => 'sometimes|nullable|numeric',
            'type_id' => 'sometimes|nullable|integer',
            'status_id' => 'sometimes|nullable|integer',
            'limit' => 'sometimes|nullable|integer',
        ]);
        if (!$request->all()) return response()->json(['message' => 'Пустые данные'], 500);
        $product = Product::findOrFail($id);
        $update = $product->update($request->all());
        if ($product && $update) {
            return response()->json($product);
        }
        return response()->json([
            'message' => 'Ошибка обновления'
        ], 500);
    }

    /**
     * Update the specified resource in storage.
     */
    public function put(Request $request, string $id)
    {
        $request->validate([
            'img' => 'required|string',
            'name' => 'required|string|max:255',
            'description' => 'required|nullable|string',
            'cost' => 'required|numeric',
            'type_id' => 'required|integer',
            'status_id' => 'required|integer',
            'limit' => 'required|integer',
        ]);
        $product = Product::findOrFail($id);
        $update = $product->update($request->all());
        if ($product && $update) {
            return response()->json($product);
        }
        return response()->json([
            'message' => 'Ошибка обновления'
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $product = Product::findOrFail($id);
        $isDeleted = $product->delete($id);
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
