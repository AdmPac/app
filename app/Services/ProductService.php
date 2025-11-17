<?php

namespace App\Services;

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

    public function post(array $request): array
    {
        $product = Product::create($request)->toArray();
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

    public function patch(array $request, int $id): bool
    {
        if (!$request) throw new \Exception('Пустые данные');
        $product = Product::findOrFail($id);
        $update = $product->update($request);
        return $update;
    }

    public function put(array $request, int $id): bool
    {
        $product = Product::findOrFail($id);
        $update = $product->update($request);
        return $update;
    }

    public function delete(int $id): bool
    {
        $product = Product::findOrFail($id);
        $isDeleted = $product->delete($id);
        return $isDeleted;
    }

}
