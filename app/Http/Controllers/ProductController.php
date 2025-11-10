<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

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
        $products = Product::where('status_id', 1)->with('status')->get();
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
            'message' => 'bad create'
        ], 500);
    }

    /**
     * Display the specified resource.
     */
    public function get(string $id)
    {
        $products = Product::where('id', $id)->where('status_id', 1)->firstOrFail();
        return response()->json($products);
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

        $product = Product::findOrFail($id);
        $update = $product->update($request->all());
        if ($product && $update) {
            return response()->json($product);
        }
        return response()->json([
            'message' => 'bad update'
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
            'message' => 'bad update'
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
                'message' => 'delete success',
            ]);
        }
        return response()->json([
            'message' => 'bad deleted',
        ], 500);
    }
}
