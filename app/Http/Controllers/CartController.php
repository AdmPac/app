<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function get()
    {
        $orderId = null;
        $productsData = [];
        if (Auth::check()) {
            $productsModel = Order::with('product', 'status')
                ->where('user_id', Auth::user()->id)
                ->where('status_id', 1) // статус=1 - текущая корзина
                ->first();
            if ($productsModel) {
                $orderId = $productsModel->id;
    
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
        return response()->json([
            'products' => $productsData,
            'allSum' => $allSum,
        ]);
    } 

    public function delete($id)
    {
        if (Auth::check()) {
            $order = Order::where('status_id' , 1)->firstOrFail();
            $item = OrderItems::where(['order_id' => $order, 'product_id' => $id])->firstOrFail();
            $item->delete();
        } else {
            $order = session('order');
            if (isset($order[$id])) unset($order[$id]);
            else return response()->json([ // TODO: привести ошибки к одному виду
                "product $id not found in the cart"
            ], 500);
            session(['order' => $order]);
        }
        return response()->json($order);
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
            $cart = Order::firstOrCreate([
                'user_id' => Auth::user()->id,
                'status_id' => 1,
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
            // $cart = Order::firstOrCreate([
            //     'user_id' => Auth::user()->id,
            //     'status_id' => 1,
            // ]);
            
            // $item = OrderItems::firstOrCreate([
            //     'product_id' => $id,
            //     'order_id' => $cart->id,
            // ],[
            //     'name' => $product->name,
            //     'img' => $product->img,
            //     'description' => $product->description,
            //     'cost' => $product->cost,
            //     'name' => $product->name,
            //     'quantity' => 0,
            // ]);
            // if (($quantity += $item->quantity) > $limit) $quantity = $limit;

            // $item->update(['quantity' => $quantity]);
        } else {
            $order = Session::get('order', []);
            if (!key_exists($id, $order)) return response()->json(
                ["product $id not found in the cart"], 500
            );
            $order[$id] = min($limit, $quantity);
            Session::put('order', $order);
        }
        return response()->json($order);
    }
}
