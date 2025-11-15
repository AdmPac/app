<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductService
{
    public function getAll(): array
    {
        $products = Product::whereHas('status', function ($q) {
            $q->where('code', 1);
        })->with('status')->get()->toArray();
        return $products;        
    }

    public function post(Request $request): array
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

        $product = Product::create($request->all())->toArray();
        return $product;
    }

    public function get(int $id): array
    {
        $product = Product::where('id', $id)
            ->whereHas('status', function ($q) {
                $q->where('code', 1);
            })
            ->firstOrFail()->toArray();
        return $product;
    }

    public function patch(Request $request, int $id): bool
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
        if (!$request->all()) throw new \Exception('Пустые данные');
        $product = Product::findOrFail($id);
        $update = $product->update($request->all());
        return $update;
    }

    public function put(Request $request, int $id): bool
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
        
        return $update;
    }

    public function delete(int $id): bool
    {
        $product = Product::findOrFail($id);
        $isDeleted = $product->delete($id);
        return $isDeleted;
    }

}
