<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Phone;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use App\Models\Order\Status as OrderStatus;

class CartController extends Controller
{
    public function get()
    {
        if (Auth::check()) { 
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
        } else {
            $quantity = session('order') ?? [];
            $productsData = Product::whereIn('id', array_keys($quantity))->get();
            $productsData = $productsData->map(function($product) use ($quantity) {
                $product->quantity = $quantity[$product->id] ?? 0;
                return $product;
            });
            $productsData = $productsData->toArray();
        }
        $allSum = 0;
        foreach ($productsData as $product) {
            $allSum += $product['quantity'] * $product['cost'];
        }
        return response()->json(
            $productsData,
        );
    } 

    public function delete($id)
    {
        if (Auth::check()) {
            $order = Order::whereHas('status', function ($q) {
                $q->where('code', 1);
            })->firstOrFail();
            $item = $order->item()->where('product_id', $id)->firstOrFail();
            $item->delete();
        } else {
            $order = session('order');
            if (isset($order[$id])) unset($order[$id]);
            else return response()->json([
                "Продукт $id не найден в корзине"
            ], 500);
            session(['order' => $order]);
        }
        return response()->json('Товар успешно удален');
    }
    
    public function post(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $limit = $product->limit;
        $request->validate([
            'quantity' => "required|integer|min:1|max:$limit"
        ]);
        $quantity = $request->quantity;
        if (Auth::check()) {
            $activeStatusId = OrderStatus::where('code', 1)->value('id');
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

            $item->update(['quantity' => $quantity]);
        } else {
            $order = Session::get('order', []);
            if (!key_exists($id, $order)) $order[$id] = 0; 
            if (($order[$id] += $quantity) > $limit) $order[$id] = $limit;
            
            Session::put('order', $order);
        }
        return response()->json($order);
    }

    public function patch(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $limit = $product->limit;
        $request->validate([
            'quantity' => "required|integer|min:1|max:$limit"
        ]);
        $quantity = $request->quantity;

        if (Auth::check()) {
            $order = Order::where('user_id', Auth::id())
                ->whereHas('status', function ($q) {
                    $q->where('code', 1);
                })
                ->firstOrFail()
                ->item()
                ->where('product_id', $id)
                ->firstOrFail();
            $updated = $order->update(['quantity' => $quantity]);
            return response()->json($order);
        } else {
            $order = Session::get('order', []);
            if (!key_exists($id, $order)) return response()->json(
                ['message' => "Продукт $id не найден в корзине"], 500
            );
            $order[$id] = min($limit, $quantity);
            Session::put('order', $order);
        }
        return response()->json($order);
    }
    
    
    public function delivery(Request $request)
    {
        $request->validate([
            'phone' => 'required|regex:/^\+?[0-9-]{9,}$/',
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
        $deliveredStatusId = OrderStatus::where('code', 2)->value('id');
        $updated = $orderUser->update([
            'status_id' => $deliveredStatusId,
            'address_id' => $address->id,
            'phone_id' => $phone->id,
        ]);
        
        if ($updated) {
            return response()->json($orderUser);
        }
        return response()->json([
            'message' => 'Ошибка обновления заказа', 500
        ]);
    }
}
