<?php

namespace App\Repository;

use App\Models\Address;
use App\Models\Order;
use App\Models\Order\OrderStatus as Status;
use App\Models\OrderItems;
use App\Models\Phone;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartRepository
{
    const ACTIVE_STATUS_CODE = 1;
    public function getUserCart(): Order
    {
        $productsModel = Order::with('products', 'status')
            ->where('user_id', Auth::id())
            ->whereHas('status', function ($q) {
                $q->where('code', 1); // статус code=1 - текущая корзина
            })
        ->first();
        
        return $productsModel;
    }

    public function findOrCreateCart(): Order
    {
        $activeStatusId = Status::where('code', self::ACTIVE_STATUS_CODE)->value('id');
        $cart = Order::firstOrCreate([
            'user_id' => Auth::id(),
            'status_id' => $activeStatusId,
        ]);
        return $cart;
    }

    public function getCart(): Order
    {
        $order = Order::whereHas('status', function ($q) {
            $q->where('code', 1);
        })->firstOrFail();
        return $order;
    }

    public function getProductCart(int $id): OrderItems
    {
        $order = $this->getCart();
        $item = $order->items()->where('product_id', $id)->firstOrFail();
        return $item;
    }

    public function deleteProduct(int $id): bool
    {
        return $this->getProductCart($id)->delete();
    }

    public function findOrCreateOrderItem(Product $product, int $id): OrderItems
    {
        $cart = $this->findOrCreateCart();

        $item = OrderItems::firstOrCreate([
            'product_id' => $id,
            'order_id' => $cart->id,
        ],[
            'name' => $product->name,
            'img' => $product->img,
            'description' => $product->description,
            'cost' => $product->cost,
            'name' => $product->name,
            'quantity' => 0,
        ]);
        
        return $item;
    }

    public function updateQuantityProductCart(int $productId, int $quantity): bool
    {
        $order = Order::where('user_id', Auth::id())
            ->whereHas('status', function ($q) {
                $q->where('code', 1);
            })
            ->firstOrFail()
            ->items()
            ->where('product_id', $productId)
            ->firstOrFail();
        $updated = $order->update(['quantity' => $quantity]);
        return $updated;
    }

    public function deliveryActiveOrder(string $phone, string $address): bool
    {
        $address = Address::firstOrCreate([
            'value' => $address,
        ]);
        $phone = Phone::firstOrCreate([
            'value' => $phone,
        ]);
        
        $deliveredStatusId = Status::where('code', self::ACTIVE_STATUS_CODE)->value('id');
        $orderUser = Order::where('user_id', Auth::id())
            ->where('status_id', $deliveredStatusId)
            ->firstOrFail();

        $updated = $orderUser->update([
            'status_id' => $deliveredStatusId,
            'address_id' => $address->id,
            'phone_id' => $phone->id,
        ]);
        return $updated;
    }

    public function getOrderActiveByUserId(int $uid): Order
    {
        $orderActive = Order::where('user_id', $uid)
            ->whereHas('status', function ($q) {
                $q->where('code', 1);
            })
            ->first();
        return $orderActive;
    }
}