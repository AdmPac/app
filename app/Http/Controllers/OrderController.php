<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::check()) {
            $products = Order::with('product', 'status')
                ->where('user_id', Auth::user()->id)
                ->where('status_id', 1) // статус=1 - текущая корзина
                ->first();
            
            $products = $products ? $products->toArray()['product'] : [];
            foreach ($products as $k => $product) {
                $products[$k]['quantity'] = $product['pivot']['quantity'];
                $products[$k]['order_id'] = $product['pivot']['order_id'];
            }
        } else {
            $quantity = session('order') ?? [];
            $products = Product::whereIn('id', array_keys($quantity))->get();
            $products = $products->map(function($product) use ($quantity) {
                $product->quantity = $quantity[$product->id] ?? 0;
                return $product;
            });
            $products = $products->toArray();
        }
        $allSum = 0;
        foreach ($products as $product) {
            $allSum += $product['quantity'] * $product['cost'];
        }
        return view('order.index', compact('products', 'allSum'));
    }

    public function delete($orderId, $id)
    {
        if (Auth::check()) {
            $item = OrderItems::where(['order_id' => $orderId, 'product_id' => $id])->first();
            $item->delete();
        } else {
            $order = session('order');
            if (isset($order[$id])) unset($order[$id]);
            session(['order' => $order]);
        }
        return redirect()->route('order.index');
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $limit = $product->limit;
        $request->validate([
            'quantity' => "integer|min:1|max:$limit"
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
            $order = session('order');
            if (!is_array($order) || !key_exists($id, $order)) $order[$id] = 0; 
            if (($order[$id] += $quantity) > $limit) $order[$id] = $limit;
            
            session(['order' => $order]);
        }
        return redirect()->route('order.index');
    }
}
