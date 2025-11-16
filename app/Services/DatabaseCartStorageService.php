<?php

namespace App\Services;

use App\Contracts\CartStorageInterface;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Repository\CartRepository;

class DatabaseCartStorageService implements CartStorageInterface
{
    public function __construct(private CartRepository $repository)
    {
    }

    public function get(): array
    {
        $productsModel = $this->repository->getUserCart();
        
        if ($productsModel) { 
            $productsData = $productsModel->toArray()['product'];
            foreach ($productsData as $k => $product) {
                $productsData[$k]['quantity'] = $product['pivot']['quantity'];
                $productsData[$k]['order_id'] = $product['pivot']['order_id'];
            }
            return $productsData;
        }
        return [];
    }

    public function delete($id): bool
    {
        return $this->repository->deleteProduct($id);
    }

    public function post(Request $request, string $id): bool
    {
        $product = Product::findOrFail($id);
        $quantity = (int) $request->quantity;
        
        $orderItem = $this->repository->findOrCreateOrderItem($product, $id);

        $limit = (int) $product->limit;
        if (($quantity += (int) $orderItem->quantity) > $limit) $quantity = $limit;
        $updated = $orderItem->update(['quantity' => $quantity]);
        
        return $updated;
    }

    public function patch(Request $request, $id): bool
    {
        $product = Product::findOrFail($id);
        $quantity = (int) $request->quantity;
        $updated = $this->repository->updateQuantityProductCart($product->id, $quantity);
        return $updated;
    }

    public function delivery(Request $request): bool
    {
        $updated = $this->repository->deliveryActiveOrder($request->phone, $request->address);
        return $updated;
    }
}