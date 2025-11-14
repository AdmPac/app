<?php

namespace App\Service;

use App\Contracts\CartStorageInterface;
use App\Models\Address;
use App\Models\Order;
use App\Models\Order\Status;
use App\Models\OrderItems;
use App\Models\Phone;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DatabaseCartStorageService implements CartStorageInterface
{
    public function get(): array
    {
        $productsModel = Order::with('product', 'status')
            ->where('user_id', Auth::user()->id)
            ->whereHas('status', function ($q) {
                $q->where('code', 1); // статус code=1 - текущая корзина
            })
        ->first();
        if ($productsModel) { 
            $productsData = $productsModel ? $productsModel->toArray()['product'] : [];
            foreach ($productsData as $k => $product) {
                $productsData[$k]['quantity'] = $product['pivot']['quantity'];
                $productsData[$k]['order_id'] = $product['pivot']['order_id'];
            }
        }
        return $productsData;
    }

    public function delete($id): bool
    {
        $order = Order::whereHas('status', function ($q) {
            $q->where('code', 1);
        })->firstOrFail();
        $item = $order->item()->where('product_id', $id)->firstOrFail();
        $deleted = $item->delete();

        return $deleted;
    }

    public function post(Request $request, string $id): bool
    {
        $product = Product::findOrFail($id);
        $limit = $product->limit;
        $request->validate([
            'quantity' => "required|integer|min:1|max:$limit"
        ]);
        $quantity = $request->quantity;

        $activeStatusId = Status::where('code', 1)->value('id');
        $cart = Order::firstOrCreate([
            'user_id' => Auth::user()->id,
            'status_id' => $activeStatusId,
        ]);
        
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
        if (($quantity += $item->quantity) > $limit) $quantity = $limit;

        $updated = $item->update(['quantity' => $quantity]);
        return $updated;
    }

    public function patch(Request $request, $id): bool
    {
        $product = Product::findOrFail($id);
        $limit = $product->limit;
        $request->validate([
            'quantity' => "required|integer|min:1|max:$limit"
        ]);
        $quantity = $request->quantity;

        $order = Order::where('user_id', Auth::id())
            ->whereHas('status', function ($q) {
                $q->where('code', 1);
            })
            ->firstOrFail()
            ->item()
            ->where('product_id', $id)
            ->firstOrFail();
        $updated = $order->update(['quantity' => $quantity]);
        return $updated;
    }

    public function delivery(Request $request): bool
    {
        
        $request->validate([
            'phone' => 'required|regex:/^\\+?[0-9-]{9,}$/',
            'address' => 'required|min:1',
        ]);
        
        $address = Address::firstOrCreate([
            'value' => $request->address,
        ]);
        $phone = Phone::firstOrCreate([
            'value' => $request->phone,
        ]);
        
        $uid = Auth::id();
        $orderUser = Order::where('user_id', $uid)
            ->whereHas('status', function ($q) {
                $q->where('code', 1);
            })
            ->firstOrFail();
        $deliveredStatusId = Status::where('code', 2)->value('id');
        $updated = $orderUser->update([
            'status_id' => $deliveredStatusId,
            'address_id' => $address->id,
            'phone_id' => $phone->id,
        ]);
        
        return $updated;
    }
}
